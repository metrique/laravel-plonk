<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Eloquent\PlonkAsset;
use Metrique\Plonk\Repositories\PlonkInterface;

class Plonk implements PlonkInterface
{
    protected $filteredQuerystring = [];
    protected $cacheTtl = 60;
    
    /**
     * {@inheritdoc}
     */
    public function resource(string $hash)
    {
        $signature = sprintf('%s::%s %s', __CLASS__, __FUNCTION__, $hash);
        
        return cache()->remember(sha1($signature), $this->cacheTtl, function () use ($hash) {
            return $this->findByHash($hash)->resource;
        });
    }
    
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
                    $search = PlonkAsset::search($value)->get()->pluck(['id']);
                    $plonkAsset->whereIn('id', $search);
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

    /**
     * {@inheritdoc}
     */
    public function update($id, $params)
    {
        return PlonkAsset::find($id)->update([
            'title' => $params['title'],
            'alt' => $params['alt'],
        ]);
    }
}
