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
		if(!$this->validates)
		{
			$this->validates = $this->requestValidates() & $this->imageValidates();
		}

		return $this->validates;
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
			throw new PlonkException('Image or Plonk update does not validate.');
		}

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

		// Create images
		$images = $this->requestImages();

		// Send each image to Filesystem
		$this->save($images);

		// Store reference to image in DB
		$this->create($images);		
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

		return implode('/', [$base, str_limit($this->getHash(), 2), $this->getHash().'-'.str_slug($name).$extension]);
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
	 * Test if the plonk form upload validates.
	 * 
	 * @return bool
	 */
	protected function requestValidates()
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
	 * Test if the image validates.
	 * 
	 * @return bool
	 */
	protected function imageValidates()
	{
		try
		{
			$this->image = $this->imageManager->make($this->file);
		}

		catch (\Intervention\Image\Exception\NotReadableException $e)
		{
			return false;
		}

		return true;
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
		$images = [];

		// Backup image in its original state.
		$this->image->backup();

		// Produce images for each size.
		foreach (config('plonk.size') as $key => $value) {

			// Reset image to original state.
			$this->image->reset();

			switch($this->getOrientation())
			{
				case PlonkOrientation::SQUARE:
				case PlonkOrientation::LANDSCAPE:
					$this->image->resize($value['width'], null, function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				break;

				case PlonkOrientation::PORTRAIT:
					$this->image->resize(null, $value['height'], function($constraint) {
						$constraint->aspectRatio();
						$constraint->upsize();
					});
				break;
			}

			// Store new images.
			array_push($images, [
				'data' => (string) $this->image->encode(null, $value['quality']),
				'name' => $value['name'],
				'width' => $this->image->width(),
				'height' => $this->image->height(),
				'quality' => $value['quality'],
			]);
		}

		// Reset image to original state for any future needs.
		$this->image->reset();

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

			unset($value['data']);
		}
	}

	protected function create(&$images)
	{
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