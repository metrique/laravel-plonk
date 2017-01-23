<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Repositories\Abstracts\EloquentRepositoryAbstract;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;

class PlonkRepositoryEloquent extends EloquentRepositoryAbstract implements PlonkRepositoryInterface
{
    protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';
    protected $filteredQuerystring = [];

    /**
     * {@inheritdoc}
     */
    public function findWithVariation($id, array $columns = ['*'], $fail = true)
    {
        if ($fail) {
            return $this->model->with('variations')->where('published', 1)->findOrFail($id, $columns);
        }

        return $this->model->with('variations')->where('published', 1)->find($id, $columns);
    }

    /**
     * {@inheritdoc}
     */
    public function findWithVariationByHash($hash, array $columns = ['*'], $fail = true)
    {
        if ($fail) {
            return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->first($columns);
        }

        return $this->model->with('variations')->where('published', 1)->where('hash', $hash)->firstOrFail($columns);
    }

    /**
     * {@inheritdoc}
     */
    public function filterRequest()
    {
        return $this->filterQuerystring(request()->input());
    }

    /**
     * {@inheritdoc}
     */
    public function filterQuerystring(array $querystring)
    {
        $keys = array_keys(array_flip(config('plonk.query.filter')));

        return collect($querystring)->only($keys);
    }

    /**
     * {@inheritdoc}
     */
    public function allFiltered()
    {
        $plonkAsset = PlonkAsset::with('variations')->where('published', 1);

        $this->filterRequest()->each(function ($value, $key) use ($plonkAsset) {
            switch ($key) {
                case 'search':
                    $plonkAsset = $plonkAsset->search($value);
                    break;

                case 'ratio':
                    $plonkAsset = $plonkAsset->whereBetween($key, [
                        $value - config('plonk.crop_tolerance'),
                        $value + config('plonk.crop_tolerance')
                    ]);
                    break;

                default:
                    $plonkAsset = $plonkAsset->where($key, $value);
                    break;
            }
        });

        return $plonkAsset;
    }

    /**
     * {@inheritdoc}
     */
    public function paginateWithVariation($perPage = 10, array $columns = ['*'], array $order = [])
    {
        $this->model = $this->allFilteredBy([]);

        return $this->paginate($perPage, $columns, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function searchAndPaginateWithVariation($query, $perPage = 10, array $columns = ['*'], array $order = [])
    {
        $this->model = $this->allFilteredBy(['search' => $query]);

        return $this->paginate($perPage, $columns, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function publish($id)
    {
        return $this->update($id, ['published' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function unpublish($id)
    {
        return $this->update($id, ['published' => 0]);
    }
}
