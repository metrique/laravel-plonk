<?php

namespace Metrique\Plonk\Repositories\Contracts;

/**
 * EloquentRepositoryInterface provides the standard
 * functions to be expected of any eloquent repository.
 */
interface EloquentRepositoryInterface { 
    public function all(array $columns = ['*']);
    public function paginate($perPage = 10, array $columns = ['*']);
    public function create(array $data);
    public function update($id, array $data);
    public function find($id, array $columns = ['*']);
    public function destroy($id);
}