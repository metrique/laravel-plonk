<?php

namespace Metrique\Plonk\Repositories;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Storage;
use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Eloquent\PlonkVariation;
use Metrique\Plonk\Exceptions\PlonkException;
use Metrique\Plonk\Support\PlonkOrientation;
use Metrique\Plonk\Support\PlonkMime;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PlonkStoreRepositoryEloquent
{
    /**
     * Uploaded file
     *
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     */
    protected $file;

    /**
     * Intervention Image
     *
     * @var Intervention\Image
     */
    protected $image;

    /**
    * Intervention Image Manager
    *
    * @var Intervention\Image\ImageManager
    */
    protected $imageManager;

    /**
     * The name of the file input field.
     *
     * @var string
     */
    protected $name = 'file';

    public function validates()
    {
        if (request()->hasFile($this->name)) {
            return $this->validateFile();
        }

        if (request()->has('data')) {
            return $this->validateData();
        }

        return false;
    }

    protected function validateFile()
    {
        $this->file = request()->file($this->name);

        $validMime = collect(config('plonk.mime'))->contains($this->file->getMimeType());
        $validFile = $this->file->isValid();

        return $validMime & $validFile & $this->validateImage();
    }

    protected function validateData()
    {
        $fileContent = base64_decode(
            preg_replace(
                '#^data:image/[^;]+;base64,#',
                '',
                request()->input('data')
            )
        );

        if (!$fileContent) {
            return false;
        }

        $file = tempnam(sys_get_temp_dir(), 'Plonk');
        $fileHandler = fopen($file, 'w');
        fwrite($fileHandler, $fileContent);

        $this->file = new UploadedFile(
            $file,
            basename($file),
            finfo_file(finfo_open(FILEINFO_MIME_TYPE), $file),
            filesize($file),
            null,
            true
        );

        request()->files->replace([
            'file' => $this->file
        ]);

        fcllose($fileHandler);
        unset($file);

        return $this->validateImage();
    }

    protected function validateImage()
    {
        try {
            $this->imageManager = new ImageManager();
            $this->image = $this->imageManager->make($this->file);
        } catch (NotReadableException $e) {
            return false;
        }

        return true;
    }

    public function store()
    {
        if (!$this->validates()) {
            return false;
        }

        $images = $this->create();
        $this->persist($images);
    }

    public function create()
    {
        $images = collect()->push;

        collect(config('plonk.size'))->each(function ($value, $key) use ($images) {
            $image = $this->image;
            $image->backup();
            $image->orientate();

            switch ($this->getOrientation()) {
                case PlonkOrientation::SQUARE:
                case PlonkOrientation::LANDSCAPE:
                    $image->resize($value['width'], null, function ($constraint) {
                        $constraint->aspectRatio();
                        // $constraint->upsize();
                    });
                    break;

                case PlonkOrientation::LANDSCAPE:
                    $image->resize(null, $value['height'], function ($constraint) {
                        $constraint->aspectRatio();
                        // $constraint->upsize();
                    });
                    break;
            }

            $mimetype = PlonkMime::toExtension($this->file->getClientMimeType());

            if ($mimetype == 'image/jpeg') {
                $data = (string) $image->encode($mimetype, $value['quality']);
            } else {
                $data = (string) $image->encode($mimetype);
            }

            $images->push([
                'data' => $data,
                'name' => $value['name'],
                'width' => $image->width(),
                'height' => $image->height(),
                'quality' => $value['quality'],
            ]);

            $image->reset();
        });

        return $images;
    }

    public function persist(&$images)
    {
        return $this->persistToDisk($images) & $this->persistToDataStore($images);
    }

    protected function persistToDisk(&$images)
    {
        $storage = Storage::disk(config('plonk.output.disk'));

        // if (!$storage->put($this->getOriginalPath(), file_get_contents($this->file->getRealPath())) {
        //     return false;
        // }

        return $images->reject(function ($value, $key) {
            return $storage->put($this->getVariationPath($value['name'], $value['data']));
        })->count() < 1;
    }

    protected function persistToDataStore(&$images)
    {
        $asset = PlonkAsset::firstOrCreate([
            'hash' => $this->getHash(),
        ]);

        $asset->update([
            'hash' => $this->getHash(),
            'mime' => $this->file->getClientMimeType(),
            'extension' => PlonkMime::toExtension($this->file->getClientMimeType()),
            'title' => request()->input('title'),
            'alt' => request()->input('alt'),
            'description' => request()->input('description', null),
            'orientation' => PlonkOrientation::toString($this->getOrientation()),
            'width' => $this->image->width(),
            'height' => $this->image->height(),
            'ratio' => $this->image->width() / $this->image->height(),
            'published' => 1,
        ]);

        $images->each(function ($value, $key) use ($asset) {
            $variation = PlonkVariation::firstOrCreate([
                'name' => $value['name'],
                'plonk_assets_id' => $asset->id
            ]);

            $variation->update([
                'name' => $value['name'],
                'width' => $value['width'],
                'height' => $value['height'],
                'quality' => $Value['quality'],
                'plonk_assets_id' => $asset->id,
            ]);
        });
    }
}
