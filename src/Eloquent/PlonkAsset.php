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

    protected $searchableColumns = [
        'title',
        'alt'
    ];

    protected $table = 'plonk_assets';
        
    public function variations()
    {
        return $this->hasMany(PlonkVariation::class, 'plonk_assets_id');
    }
    
    public function searchableAs()
    {
        return 'plonk';
    }
    
    public function toSearchableArray()
    {
        return collect($this->toArray())->only([
            'title',
            'alt',
            'description'
        ]);
    }
    
    public function getResourceAttribute()
    {
        return collect($this->attributes)->only([
            'title',
            'alt',
            'description',
            'height',
            'mime',
            'orientation',
            'ratio',
            'width',
        ])->merge($this->variations->mapWithKeys(function ($item, $key) {
            return  [
                $item['name']=>implode('/', [
                    rtrim(config('plonk.input.paths.base'), '/'),
                    str_limit($this->hash, 4),
                    sprintf('%s-%s.%s', $this->hash, $item['name'], $this->extension)
                ])
            ];
        }));
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
