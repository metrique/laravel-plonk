<?php

namespace Metrique\Plonk\Helpers;

class PlonkOrientation
{
	const SQUARE = 0;
	const LANDSCAPE = 1;
	const PORTRAIT = 2;

	public static function determine($width, $height)
	{
		if($width > $height)
		{
			return self::LANDSCAPE;
		}

		if($width < $height)
		{
			return self::PORTRAIT;
		}

		if($width == $height)
		{
			return self::SQUARE;
		}

		return false;
	}

	public static function toString($orientation)
	{
		switch($orientation)
		{
			case 0:
				return 'square';

			case 1:
				return 'landscape';

			case 2:
				return 'portrait';

			default:
				return false;
		}
	}
}