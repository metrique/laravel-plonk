<?php

namespace Metrique\Plonk\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

interface PlonkInterface
{
    /**
     * Construct a plonk resource from a given hash id.
     * @param  string  $hash
     * @return Collection
     */
    public function resource(string $hash);
    
    /**
     * Find a plonk asset by ID, and include all image varations.
     *
     * @param  integer $id
     * @return mixed
     */
    public function find($id);

    /**
     * Find a plonk asset by Hash, and inlcude all image varations.
     *
     * @param  integer  $hash
     * @return mixed
     */
    public function findByHash($hash);
    
    /**
     * Returns if the plonk asset hash exists.
     *
     * @param  string $hash
     * @return boolean
     */
    public function hashExists($hash);
    
    /**
     * Clean any unwanted parameters from the query string.
     *
     * @param  array  $querystring
     * @return array
     */
    public function filterQuerystring(array $querystring);

    /**
     * Clean any unwanted parameters from the request.
     *
     * @return array
     */
    public function filterRequest();

    /**
     * Get assets and query database with key/value pairs from the query string.
     *
     * @return mixed
     */
    public function allFiltered();

    /**
     * Publish asset.
     *
     * @param  integer $id
     * @return mixed
     */
    public function publish($id);

    /**
     * Unpublish asset.
     *
     * @param  integer $id
     * @return mixed
     */
    public function unpublish($id);

    /**
     * Update PlonkAsset
     * @param  integer $id
     * @param  array $params
     * @return mixed
     */
    public function update($id, $params);
}
