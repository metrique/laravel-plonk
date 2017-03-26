<?php

namespace Metrique\Plonk;

use Illuminate\Support\Facades\Facade;
use Metrique\Plonk\Plonk;

class PlonkFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Plonk::class;
    }
}
