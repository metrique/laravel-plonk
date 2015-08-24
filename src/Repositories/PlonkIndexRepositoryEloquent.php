<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstract;
use Metrique\Plonk\Repositories\Contracts\PlonkIndexRepositoryInterface;

class PlonkIndexRepositoryEloquent extends EloquentRepositoryAbstract implements PlonkIndexRepositoryInterface
{
	protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';
}