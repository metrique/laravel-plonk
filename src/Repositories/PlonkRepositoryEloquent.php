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
            return $this->model->with('variations')->where('published', 1)->findOrFail($id, $columns);
        }
        
        return $this->model->with('variations')->where('published', 1)->find($id, $columns);
    }

    public function findWithVariationByHash($hash, array $columns = ['*'], $fail = true)
    {
        if($fail)
        {
            return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->first($columns);    
        }
        return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->firstOrFail($columns);
    }

    public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = [])
    {
    	$this->model = $this->model->with('variations')->where('published', 1);

    	return $this->paginate($perPage, $columns, $order);
    }

    public function publish($id)
    {
        $this->update($id, ['published' => 1]);
    }

    public function unpublish($id)
    {
        $this->update($id, ['published' => 0]);
    }
}