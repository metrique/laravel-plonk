<?php

namespace Metrique\Plonk\Repositories\Contracts;

interface PlonkStoreRepositoryInterface
{

    /**
     * Test if both image and Plonk form upload validates.
     * The File and Image references will be created during this process.
     *
     * @return bool
     */
    public function validates();

    /**
     * Create and persist the images to file system & create their entries in the database.
     *
     * @return bool
     */
    public function store();

    /**
     * Create the images and return the data in an array.
     *
     * @return array
     */
    // public function create();

    /**
     * Persist the created images to disk and database.
     *
     * @return bool
     */
    public function persist();

    /**
     * Create the images, and save to file system & create their entries in the database.
     *
     * @return bool
     */
    public function storeCli($file, $title, $alt, $description);

    /**
     * Requests and returns a SHA256 hash of the image file contents.
     *
     * @return string
     */
    public function getHash();

    /**
     * Requests and returns an orientation type.
     *
     * @return int
     */
    public function getOrientation();

    /**
     * Constructs the path where the original file is to be stored on the file system.
     * @return string
     */
    public function getOriginalPath();

    /**
     * Constructs the path where the variations are to be stored on the file system.
     * A variation should have a unique name which should be passed into the path.
     *
     * @param  $string $name
     * @return string
     */
    public function getVariationPath($name);

    /**
     * Provides a list of ratios that the application should accept.
     * Ratio cropping will usually be handled by the front end, before being uploaded to plonk.
     * For eg. http://fengyuanchen.github.io/cropper/
     *
     * @return array
     */
    public function getCropRatios();
}
