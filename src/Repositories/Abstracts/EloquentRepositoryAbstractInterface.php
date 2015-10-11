<?php

namespace TG\Repositories\Abstracts;

/**
 * EloquentRepositoryInterface provides the standard
 * functions to be expected of any eloquent repository.
 */
interface EloquentRepositoryAbstractInterface { 
	public function make();
	public function toArray();
    public function all(array $columns = ['*'], array $order = []);
    public function paginate($perPage = 10, array $columns = ['*'], array $order = []);
    public function create(array $data);
    public function update($id, array $data);
    public function find($id, array $columns = ['*'], $fail = true);
    public function destroy($id);
    public function orderBy(array $order);
}