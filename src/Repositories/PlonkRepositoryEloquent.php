<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstract;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;

class PlonkRepositoryEloquent extends EloquentRepositoryAbstract implements PlonkRepositoryInterface
{
    protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';

    public function findWithVariation($id, array $columns = ['*'], $fail = true)
    {
        if($fail)
        {
            return $this->model->with('variations')->findOrFail($id, $columns);
        }
        
        return $this->model->with('variations')->find($id, $columns);
    }

    public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = [])
    {
    	$this->model = $this->model->with('variations');

    	return $this->paginate($perPage, $columns, $order);
    }
}