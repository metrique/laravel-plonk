<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Sofa\Eloquence\Eloquence;

class PlonkAsset extends Model
{
	use Eloquence;

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
	
	protected $table = 'plonk_assets';

	protected $searchableColumns = ['title', 'alt'];

	public function variations() {
		return $this->hasMany('Metrique\Plonk\Eloquent\PlonkVariation', 'plonk_assets_id');
	}
}