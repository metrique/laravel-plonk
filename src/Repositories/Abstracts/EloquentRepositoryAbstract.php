<?php

namespace Metrique\Plonk\Repositories\Abstracts;

use Illuminate\Container\Container;
use Metrique\Plonk\Repositories\Contracts\EloquentRepositoryInterface;

/**
 * The Abstract Repository provides default implementations of the methods defined
 * in the base repository interface. These simply delegate static function calls 
 * to the right eloquent model based on the $modelClassName.
 * 
 */
abstract class EloquentRepositoryAbstract implements EloquentRepositoryAbstractInterface {
    
    protected $app;
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
            Throw new EloquentAbstractException('Model class name is not set.');
        }

        $this->app = $app;
        $this->make();

        return $this;
    }

    public function make()
    {
        $this->model = $this->app->make($this->modelClassName);
    }

    public function toArray()
    {
        return $this->model->get()->toArray();
    }

    public function all(array $columns = ['*'], array $order = [])
    {
        if(count($order) > 0)
        {
            foreach($order as $key => $value)
            {
                $this->model = $this->model->orderBy($key, $value);
            }
            
            return $this->model->get($columns);
        }

        return $this->model->all($columns);
    }

    public function paginate($perPage = 10, array $columns = ['*'], array $order = [])
    {
        if(count($order) > 0)
        {
            foreach($order as $key => $value)
            {
                $this->model = $this->model->orderBy($key, $value);
            }
        }

        return $this->model->paginate($perPage, $columns);
    }

    public function create(array $data)
    {
        $model = $this->model->create($data);

        if(!$model)
        {
            Throw new EloquentAbstractException('Model was not created.');
        }

        return $model;
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id)->update($data);

        if(!$model)
        {
            Throw new EloquentAbstractException('Model was not updated.');
        }

        return $model;
    }

    public function find($id, array $columns = ['*'], $fail = true)
    {
        if($fail)
        {
            return $this->model->findOrFail($id, $columns);
        }
        
        return $this->model->find($id, $columns);
    }

    public function destroy($id)
    {
        $model = $this->model->destroy($id);

        if(!$model)
        {
            Throw new EloquentAbstractException('Model was not deleted.');
        }

        return $model;
    }

    public function orderBy(array $order)
    {
        if(count($order) > 0)
        {
            foreach($order as $key => $value)
            {
                $this->model = $this->model->orderBy($key, $value);
            }
        }

        return $this->model;
    }
}