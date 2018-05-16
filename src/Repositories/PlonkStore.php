<?php

namespace Metrique\Plonk\Repositories;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Exception\NotReadableException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Eloquent\PlonkVariation;
use Metrique\Plonk\Exceptions\PlonkException;
use Metrique\Plonk\Support\PlonkOrientation;
use Metrique\Plonk\Support\PlonkMime;
use Metrique\Plonk\Repositories\PlonkStoreInterface;

// use Symfony\Component\HttpFoundation\File\UploadedFile;

class PlonkStore implements PlonkStoreInterface
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
        if (request()->has('data')) {
            $this->makeFileFromData();
        }
        
        return $this->validateFile() & $this->validateImage();
    }

    protected function validateFile()
    {
        $this->file = request()->file;
        $validMime = collect(config('plonk.mime'))->contains($this->file->getMimeType());

        $validFile = $this->file->isValid();
        return $validMime & $validFile;
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
    
    public function makeFileFromData()
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
        
        fclose($fileHandler);
        unset($file);
    }

    public function store()
    {
        if (!$this->validates()) {
            return false;
        }
        
        $images = $this->resizeImages();
        $this->persist($images);
    }
    
    public function storeCli($path, $title, $alt)
    {
        // $this->imageManager = new ImageManager();

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        
        $uploadedFile = new UploadedFile(
            $path,
            basename($path),
            finfo_file($finfo, $path),
            filesize($path),
            null,
            true
        );
        
        request()->files->replace([
            $this->name => $uploadedFile
        ]);

        request()->merge([
            'title' => $title,
            'alt' => $alt,
        ]);
        
        unset($uploadedFile);
        unset($finfo);
        
        return $this->store();
    }

    public function resizeImages()
    {
        $images = collect([]);

        collect(config('plonk.size'))->each(function ($value, $key) use ($images) {
            $image = $this->image;
            
            $image->backup();
            $image->orientate();

            switch ($this->getOrientation()) {
                case PlonkOrientation::SQUARE:
                case PlonkOrientation::LANDSCAPE:
                    $image->resize($value['width'], null, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    break;

                case PlonkOrientation::PORTRAIT:
                    $image->resize(null, $value['height'], function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
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

    public function persist(Collection &$images)
    {
        return $this->persistToDisk($images) & $this->persistToDataStore($images);
    }

    protected function persistToDisk(Collection &$images)
    {
        $storage = Storage::disk(config('plonk.output.disk'));

        return $images->reject(function ($value, $key) use ($storage) {
            return $storage->put($this->getVariationPath($value['name']), $value['data']);
        })->count() < 1;
    }

    protected function persistToDataStore(Collection &$images)
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
                'quality' => $value['quality'],
                'plonk_assets_id' => $asset->id,
            ]);
        });
    }
    
    public function getHash()
    {
        if (!isset($this->hash)) {
            $this->hash = hash_file('sha256', $this->file->getRealPath());
        }
        
        return $this->hash;
    }
    
    public function getOrientation()
    {
        if (!isset($this->orientation)) {
            $this->orientation = PlonkOrientation::determine($this->image->width(), $this->image->height());
        }
        
        return $this->orientation;
    }
    
    public function getOriginalPath()
    {
        $base = rtrim(config('plonk.output.paths.base'), '/');
        $original = trim(config('plonk.output.paths.originals'), '/');
        $extension = '.' . PlonkMime::toExtension($this->image->mime());

        return implode('/', [$base, $original, $this->getHash().$extension]);
    }
    
    public function getVariationPath($name)
    {
        $base = rtrim(config('plonk.output.paths.base'), '/');
        $original = trim(config('plonk.output.paths.originals'), '/');
        $extension = '.' . PlonkMime::toExtension($this->file->getClientMimeType());

        return implode('/', [$base, str_limit($this->getHash(), 4), $this->getHash().'-'.str_slug($name).$extension]);
    }
}
