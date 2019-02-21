<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Metrique\Plonk\Eloquent\PlonkVariation;

class PlonkAsset extends Model
{
    protected $appends = [
        'resource',
        'small',
        'large',
    ];

    protected $fillable = [
        'params',
        'hash',
        'mime',
        'extension',
        'title',
        'alt',
        'description',
        'description',
        'orientation',
        'width',
        'height',
        'ratio',
        'published'
    ];

    public function variations()
    {
        return $this->hasMany(PlonkVariation::class, 'plonk_assets_id');
    }

    public function getResourceAttribute()
    {
        $variations = $this->variations->mapWithKeys(function ($item, $key) {
            return  [
                $item['name'] => [
                    'path' => implode('/', [
                        rtrim(config('plonk.input.paths.base'), '/'),
                        str_limit($this->hash, 4),
                        sprintf('%s-%s.%s', $this->hash, $item['name'], $this->extension)
                    ]),
                    'width' => $item['width'],
                    'height' => $item['height'],
                    'quality' => $item['quality']
                ]
            ];
        });

        $sizes = $variations->mapWithKeys(function ($item, $key) {
            return [
                'variation_'.$key => $item['path']
            ];
        });

        return collect($this->attributes)->only([
            'title',
            'alt',
            'height',
            'mime',
            'orientation',
            'ratio',
            'width',
        ])->merge([
            'variations' => $variations->toArray(),
            'smallest' => $this->getSmallAttribute(),
            'largest' => $this->getLargeAttribute(),
        ])->merge($sizes);
    }

    public function getSmallAttribute()
    {
        $width = PHP_INT_MAX;

        foreach ($this->variations as $key => $value) {
            if ($value->width < $width) {
                $width = $value->width;
                $select = $value;
            }
        }

        if (!isset($select)) {
            return null;
        }

        return implode('/', [
            config('plonk.url', ''),
            rtrim(config('plonk.input.paths.base'), '/'),
            str_limit($this->hash, 4),
            sprintf('%s-%s.%s', $this->hash, $select->name, $this->extension)
        ]);
    }

    public function getLargeAttribute()
    {
        $width = 0;

        foreach ($this->variations as $key => $value) {
            if ($value->width > $width) {
                $width = $value->width;
                $select = $value;
            }
        }

        if (!isset($select)) {
            return null;
        }

        return implode('/', [
            config('plonk.url', ''),
            rtrim(config('plonk.input.paths.base'), '/'),
            str_limit($this->hash, 4),
            sprintf('%s-%s.%s', $this->hash, $select->name, $this->extension)
        ]);
    }
}
