<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\HookInterface;

class Hook implements HookInterface
{
    public $map = [
    ];

    public function hook($pointer)
    {
        return collect($this->map)->filter(function ($item, $key) use ($pointer) {
            return fnmatch($key, get_class($pointer), FNM_NOESCAPE);
        })->unique()->each(function ($item, $key) use ($pointer) {
            if (method_exists($this, $item)) {
                $this->{$item}($pointer);
            }
        });

        return false;
    }
}
