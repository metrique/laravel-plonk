<?php

namespace Metrique\Plonk\Repositories\Contracts;

use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstractInterface;

interface PlonkRepositoryInterface extends EloquentRepositoryAbstractInterface
{
	public function findWithVariation($id, array $columns = ['*'], $fail = true);
	public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = []);	
	public function publish($id);
	public function unpublish($id);
}