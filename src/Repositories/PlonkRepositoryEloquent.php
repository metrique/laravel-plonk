<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstract;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;

class PlonkRepositoryEloquent extends EloquentRepositoryAbstract implements PlonkRepositoryInterface
{
    protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';
    protected $filteredQuerystring = [];

    /**
     * {@inheritdoc}
     */
    public function findWithVariation($id, array $columns = ['*'], $fail = true)
    {
        if($fail)
        {
            return $this->model->with('variations')->where('published', 1)->findOrFail($id, $columns);
        }
        
        return $this->model->with('variations')->where('published', 1)->find($id, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function findWithVariationByHash($hash, array $columns = ['*'], $fail = true)
    {
        if($fail)
        {
            return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->first($columns);    
        }

        return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->firstOrFail($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function filterQuerystring(array $querystring)
    {
        $this->filteredQuerystring = array_intersect_key($querystring, array_flip(config('plonk.query.filter')));

        return $this->filteredQuerystring;
    }

    /**
     * {@inheritdoc}
     */
    public function querystring()
    {
        return $this->filteredQuerystring;
    }

    /**
     * {@inheritdoc}
     */
    public function allFilteredBy(array $querystring)
    {
        $this->filterQuerystring($querystring);

        $this->model = $this->model->with('variations')->where('published', 1);

        foreach($this->filteredQuerystring as $key => $value)
        {
            switch($key)
            {
                case 'search':
                    $this->model = $this->model->search($value);
                break;

                case 'ratio':
                    $this->model = $this->model->whereBetween($key, [$value - config('plonk.crop_tolerance'), $value + config('plonk.crop_tolerance')]);
                break;

                default:
                    $this->model = $this->model->where($key, $value);
                break;
            }
        }

        return $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = [])
    {
    	$this->model = $this->allFilteredBy([]);

    	return $this->paginate($perPage, $columns, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function searchAndPaginateWithVariation($query, $perPage = 10, array $columns = ['*'], array $order = [])
    {
        $this->model = $this->allFilteredBy(['search' => $query]);

        return $this->paginate($perPage, $columns, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function publish($id)
    {
        return $this->update($id, ['published' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function unpublish($id)
    {
        return $this->update($id, ['published' => 0]);
    }
}