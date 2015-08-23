<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PlonkVariation extends Model
{
	protected $fillable = ['name', 'width', 'height', 'ratio', 'quality', 'plonk_assets_id'];
}