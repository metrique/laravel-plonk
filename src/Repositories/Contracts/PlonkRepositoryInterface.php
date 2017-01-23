<?php

namespace Metrique\Plonk\Repositories\Contracts;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstractInterface;

interface PlonkRepositoryInterface extends EloquentRepositoryAbstractInterface
{
    /**
     * Find a plonk asset by ID, and include all image varations.
     * @param  integer $id
     * @param  array $columns
     * @param  boolean $fail
     * @return mixed
     */
    public function findWithVariation($id, array $columns = ['*'], $fail = true);

    /**
     * Find a plonk asset by Hash, and inlcude all image varations.
     * @param  integer  $hash
     * @param  array   $columns
     * @param  boolean $fail
     * @return mixed
     */
    public function findWithVariationByHash($hash, array $columns = ['*'], $fail = true);

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
     * [paginateWithVariation description]
     * @param  integer $perPage
     * @param  array   $columns
     * @param  array   $order
     * @return LengthAwarePaginator
     */
    public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = []);

    /**
     * Search and paginate plonk, include all image variations.
     *
     * @param  string  $query
     * @param  integer $perPage
     * @param  array   $columns
     * @param  array   $order
     * @return LengthAwarePaginator
     */
    public function searchAndPaginateWithVariation($query, $perPage = 10, array $columns = ['*'], array $order = []);

    /**
     * Publish asset.
     * @param  integer $id
     * @return mixed
     */
    public function publish($id);

    /**
     * Unpublish asset.
     * @param  integer $id
     * @return mixed
     */
    public function unpublish($id);
}
