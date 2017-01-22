<?php

namespace Metrique\Plonk\Support;

class PlonkMime
{
    public static function toExtension($mimeType)
    {
        switch ($mimeType) {
            case 'image/gif':
                return 'gif';
            break;

            case 'image/jpeg':
                return 'jpg';
            break;

            case 'image/png':
                return 'png';
            break;
        }

        return false;
    }

    public static function toMime($extension)
    {
        switch ($mimeType) {
            case 'gif':
                return 'image/gif';
            break;

            case 'jpg':
            case 'jpeg':
                return 'image/jpeg';
            break;

            case 'png':
                return 'image/png';
            break;
        }

        return false;
    }
}
