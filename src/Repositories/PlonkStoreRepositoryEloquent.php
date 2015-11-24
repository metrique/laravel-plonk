<?php

namespace Metrique\Plonk\Repositories;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Eloquent\PlonkVariation;
use Metrique\Plonk\Exceptions\PlonkException;
use Metrique\Plonk\Helpers\PlonkOrientation;
use Metrique\Plonk\Helpers\PlonkMime;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface;

class PlonkStoreRepositoryEloquent implements PlonkStoreRepositoryInterface
{
	protected $inputName = 'file';

	/**
	 * Uploaded file
	 * @var Symfony\Component\HttpFoundation\File\UploadedFile
	 */
	protected $file;

	/**
	 * SHA256 hash of image content
	 * @var string
	 */
	protected $hash;

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
	 * Image orientation
	 * @var int
	 */
	protected $orientation;

	/**
	 * Laravel Request
	 * @var Illuminate\Http\Request
	 */
	protected $request;

	/**
	 * Marks if the request has been validated.
	 * @var boolean
	 */
	protected $validates;

	public function __construct(ImageManager $imageManager, Request $request)
	{
		$this->imageManager = $imageManager;
		$this->request = $request;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return bool
	 */
	public function validates()
	{
		return $this->requestValidates();
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return bool
	 */
	public function validatesWithException()
	{
		if(!$this->validates())
		{
			throw new PlonkException('Plonk request did not validate.');
		}

		return true;
	}

	/**
	 * Test if the plonk form upload validates.
	 * 
	 * @return bool
	 */
	protected function requestValidates()
	{
		if($this->request->hasFile($this->inputName))
		{
			return $this->requestWithFileValidates();
		}

		return $this->requestWithDataValidates();

	}

	/**
	 * Request with File validates
	 */
	protected function requestWithFileValidates()
	{
		// Check if input has file
		if(!$this->request->hasFile($this->inputName))
		{
			return false;
		}

		// Store file reference.
		$this->file = $this->request->file($this->inputName);

		// Check if mime type matches allowed mimetypes in config
		if(!in_array($this->file->getMimeType(), config('plonk.mime')))
		{
			return false;
		}

		// Check if file is valid
		if(!$this->file->isValid())
		{
			return false;
		}

		return true;
	}

	/**
	 * Request with Data validates.
	 * This method can test for type of encoding. For the moment we will just test for Base64.
	 */
	public function requestWithDataValidates()
	{
		$fileContents = base64_decode(ltrim($this->request->input('data'), 'data:image/jpeg;base64,'));

		if(!$fileContents)
		{
			return false;
		}
		
		$file = tempnam(sys_get_temp_dir(), 'Plonk');
		$fileHandler = fopen($file, 'w');
		fwrite($fileHandler, $fileContents);

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile($file, basename($file), finfo_file($finfo, $file), filesize($file), null, true);
		$this->request->files->replace(['file' => $uploadedFile]);

		fclose($fileHandler);
		unset($file);

		return true;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return bool
	 */
	public function store()
	{
		// Validate request and image...
		$this->validatesWithException();
		$this->validateImageWithException();

		// Request and create images.
		$images = $this->requestImages();

		// Send each image to Filesystem
		$this->save($images);

		// Store reference to image in DB
		$this->create($images);

		unset($images);

		$this->reset();

		return true;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return bool
	 */
	public function storeCli($file, $title, $alt, $description)
	{
		$this->imageManager = new ImageManager();
		$this->request = new Request();

		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile($file, basename($file), finfo_file($finfo, $file), filesize($file), null, true);
		$this->request->files->replace(['file' => $uploadedFile]);

		$this->request->merge([
			'title' => $title,
			'alt' => $alt,
			'description' => $description,
		]);

		unset($uploadedFile);
		unset($finfo);
		
		return $this->store();
	}

	/**
	 * Validate the image.
	 * 
	 * @return bool
	 */
	protected function validateImage()
	{
		if(isset($this->image)) unset($this->image);
		if(isset($this->imageManager)) unset($this->imageManager);

		try
		{
			$this->imageManager = new ImageManager();
			$this->image = $this->imageManager->make($this->file);
		}

		catch (\Intervention\Image\Exception\NotReadableException $e)
		{
			return false;
		}

		return true;
	}

	/**
	 * Validate the image with an exception.
	 * 
	 * @return bool
	 */
	protected function validateImageWithException()
	{
		if(!$this->validateImage())
		{
			throw new PlonkException('Plonk image did not validate.');
		}
		
		return true;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return string
	 */
	public function getHash()
	{
		if(!isset($this->hash))
		{
			$this->requestHash();
		}

		return $this->hash;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return int
	 */
	public function getOrientation()
	{
		if(!isset($this->orientation))
		{
			$this->requestOrientation();
		}

		return $this->orientation;
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return string
	 */
	public function getOriginalPath()
	{
		$base = rtrim(config('plonk.output.paths.base'), '/');
		$original = trim(config('plonk.output.paths.originals'), '/');
		$extension = '.' . PlonkMime::toExtension($this->image->mime());

		return implode('/', [$base, $original, $this->getHash().$extension]);
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return string
	 */
	public function getVariationPath($name)
	{
		$base = rtrim(config('plonk.output.paths.base'), '/');
		$original = trim(config('plonk.output.paths.originals'), '/');
		$extension = '.' . PlonkMime::toExtension($this->file->getClientMimeType());

		return implode('/', [$base, str_limit($this->getHash(), 4), $this->getHash().'-'.str_slug($name).$extension]);
	}

	public function getCropRatios()
	{
		return config('plonk.crop');
	}

	/**
	 * {@inheritdoc}
	 * 
	 * @return void
	 */
	public function reset()
	{
		unset($this->file);
		unset($this->image);
		unset($this->hash);
		unset($this->orientation);
		unset($this->validates);
	}

	/**
	 * Request hash of image upload data.
	 * 
	 * @return string
	 */
	protected function requestHash()
	{
		// Validate request...
		$this->validatesWithException();

		return $this->hash = hash_file('sha256', $this->file->getRealPath());
	}

	/**
	 * Request orientation of image upload.
	 * 
	 * @return int
	 */
	protected function requestOrientation()
	{
		$this->validatesWithException();

		return $this->orientation = PlonkOrientation::determine($this->image->width(), $this->image->height());
	}

	/**
	 * Create the images to the sizes given in the plonk config.
	 * 
	 * @return array
	 */
	protected function requestImages()
	{
		// Container for image data.
		$images = [];

		// Produce images for each size.
		foreach (config('plonk.size') as $key => $value) {

			$image = clone($this->image);
			$image->orientate();

			switch($this->getOrientation())
			{
				case PlonkOrientation::SQUARE:
				case PlonkOrientation::LANDSCAPE:
					$image->resize($value['width'], null, function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				break;

				case PlonkOrientation::PORTRAIT:
					$image->resize(null, $value['height'], function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				break;
			}

			// Store new images.
			array_push($images, [
				'data' => (string) $image->encode(null, $value['quality']),
				'name' => $value['name'],
				'width' => $image->width(),
				'height' => $image->height(),
				'quality' => $value['quality'],
			]);

			unset($image);
		}

		return $images;
	}

	protected function save(&$images)
	{
		// Get reference to disk.
		$storage = Storage::disk(config('plonk.output.disk'));

		if(!$storage->put($this->getOriginalPath(), file_get_contents($this->file->getRealPath())))
		{
			throw new PlonkException('Plonk could not store file.');
		}

		// Store variations.
		foreach($images as $key => $value)
		{
			// Store variation
			if(!$storage->put($this->getVariationPath($value['name']), $value['data']))
			{
				throw new PlonkException('Plonk could not store file.');
			}

			unset($images[$key]['data']);
		}

		unset($storage);
	}


	protected function create(&$images)
	{
		\DB::connection()->disableQueryLog();

		// Create asset entry.
		$asset = PlonkAsset::firstOrCreate([
			'hash' => $this->getHash(),
		]);

		// Update asset information.
		$asset->update([
			'hash' => $this->getHash(),
			'mime' => $this->file->getClientMimeType(),
			'extension' => PlonkMime::toExtension($this->file->getClientMimeType()),
			'title' => $this->request->input('title'),
			'alt' => $this->request->input('alt'),
			'description' => $this->request->input('description', null),
			'orientation' => PlonkOrientation::toString($this->getOrientation()),
			'width' => $this->image->width(),
			'height' => $this->image->height(),
			'ratio' => $this->image->width() / $this->image->height(),
		]);

		foreach ($images as $key => $value) {

			// Search for existing named entry.
			$variation = PlonkVariation::firstOrCreate([
				'name' => $value['name'],
				'plonk_assets_id' => $asset->id,
			]);

			// Update variation information.
			$variation->update([
				'name' => $value['name'],
				'width' => $value['width'],
				'height' => $value['height'],
				'quality' => $value['quality'],
				'plonk_assets_id' => $asset->id,
			]);
		}
	}
}