<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Metrique\Plonk\Eloquent\PlonkVariation;

class PlonkAsset extends Model
{
    protected $fillable = [
        'params',
        'hash',
        'mime',
        'extension',
        'title',
        'alt',
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
}
