<?php

namespace Metrique\Plonk\Eloquent;

use Illuminate\Database\Eloquent\Model;

class PlonkAsset extends Model
{
	protected $fillable = ['params', 'hash', 'mime', 'extension', 'title', 'alt', 'description', 'orientation', 'width', 'height', 'ratio', 'published'];

	public function variations() {
		return '';
	}
}