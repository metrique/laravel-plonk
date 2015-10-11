<?php

namespace Metrique\Plonk\Repositories\Contracts;

interface PlonkRepositoryInterface extends EloquentRepositoryInterface
{
	public function findWithVariation($id, array $columns = ['*'], $fail = true);
	public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = []);
}