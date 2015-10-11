<?php

namespace Metrique\Plonk;

use Illuminate\Support\Facades\Facade;

class PlonkFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return '\Metrique\Plonk\Plonk';
    }
}