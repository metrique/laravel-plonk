<?php

namespace Metrique\Plonk\Repositories\Abstracts;

use Illuminate\Container\Container;
use Metrique\Plonk\Repositories\Contracts\EloquentRepositoryInterface;

/**
 * The Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. These simply delegate static function calls 
 * to the right eloquent model based on the $modelClassName.
 *
 * 
 */
abstract class EloquentRepositoryAbstract implements EloquentRepositoryInterface {
    
    protected $model;
    protected $modelClassName;

    /**
     * [__construct description]
     * @param Container $app 
     */
    public function __construct(Container $app)
    {
        if(is_null($this->modelClassName))
        {
            Throw new \Exception('Model class name is not set.');
        }

        $this->model = $app->make($this->modelClassName);

        return $this;
    }

    public function all(array $columns = ['*'])
    {
        return $this->model->all($columns);
    }

    public function paginate($perPage = 10, array $columns = ['*'])
    {
        return $this->model->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        return $this->model->find($id)->update($data);
    }

    public function find($id, array $columns = ['*'])
    {
        return $this->model->find($id, $columns);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }
}