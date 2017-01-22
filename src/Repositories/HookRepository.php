<?php

namespace Metrique\Plonk\Repositories;

use Metrique\Plonk\Repositories\Contracts\HookRepositoryInterface;

class HookRepository implements HookRepositoryInterface
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
