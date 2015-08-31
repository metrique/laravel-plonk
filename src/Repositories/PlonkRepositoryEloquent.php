<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstract;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;

class PlonkRepositoryEloquent extends EloquentRepositoryAbstract implements PlonkRepositoryInterface
{
	protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';

	public function paginate($perPage = 10, array $columns = ['*'])
    {
        return $this->model->orderBy('id', 'desc')->paginate($perPage, $columns);
    }
}