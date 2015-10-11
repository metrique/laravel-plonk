<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PlonkAsset extends Model
{
	protected $fillable = ['params', 'hash', 'mime', 'extension', 'title', 'alt', 'description', 'orientation', 'width', 'height', 'ratio', 'published'];
	protected $table = 'plonk_assets';

	public function variations() {
		return $this->hasMany('Metrique\Plonk\Eloquent\PlonkVariation', 'plonk_assets_id');
	}
}