<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Metrique\Plonk\Eloquent\PlonkVariation;

class PlonkAsset extends Model
{
    use Searchable;
    
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
}
