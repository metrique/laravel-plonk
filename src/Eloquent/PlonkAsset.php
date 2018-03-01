<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Metrique\Plonk\Eloquent\PlonkVariation;

class PlonkAsset extends Model
{
    use Searchable;
    
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
    
    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return [
            'id' => $this->getKey(),
            'title' => $this->title,
            'alt' => $this->alt,
            'description' => $this->description,
        ];
    }
    
    public function variations()
    {
        return $this->hasMany(PlonkVariation::class, 'plonk_assets_id');
    }
    
    public function getResourceAttribute()
    {
        return collect($this->attributes)->only([
            'title',
            'alt',
            'height',
            'mime',
            'orientation',
            'ratio',
            'width',
        ])->merge([
            'variations' => $this->variations->mapWithKeys(function ($item, $key) {
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
            })->toArray()
        ])->merge([
            'smallest' => $this->getSmallAttribute(),
            'largest' => $this->getLargeAttribute(),
        ]);
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
            rtrim(config('plonk.input.paths.base'), '/'),
            str_limit($this->hash, 4),
            sprintf('%s-%s.%s', $this->hash, $select->name, $this->extension)
        ]);
    }
}
