<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;

class PlonkRepositoryEloquent implements PlonkRepositoryInterface
{
    protected $modelClassName = 'Metrique\Plonk\Eloquent\PlonkAsset';
    protected $filteredQuerystring = [];

    /**
     * {@inheritdoc}
     */
    public function find($id)
    {
        return PlonkAsset::with('variations')->where('published', 1)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    public function findByHash($hash, array $columns = ['*'], $fail = true)
    {
        return PlonkAsset::with('variations')->where([
            'published' => 1,
            'hash' => $hash
        ])->first();
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
        $plonkAsset = PlonkAsset::with('variations')->where('published', 1)->orderBy('created_at', 'desc');

        $this->filterRequest()->each(function ($value, $key) use ($plonkAsset) {
            $cropTolerance = config('plonk.crop_tolerance');

            switch ($key) {
                case 'search':
                    $plonkAsset = $plonkAsset->search($value);
                    break;

                case 'ratio':
                    $plonkAsset = $plonkAsset->whereBetween($key, [
                        $value - $cropTolerance,
                        $value + $cropTolerance
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
    public function publish($id)
    {
        return PlonkAsset::find($id)->update(['published' => 1]);
    }

    /**
     * {@inheritdoc}
     */
    public function unpublish($id)
    {
        return PlonkAsset::find($id)->update(['published' => 0]);
    }
}
