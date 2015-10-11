<?php

namespace Metrique\Plonk;

use Metrique\Plonk\Contracts\PlonkInterface;
use Stringy\Stringy;

class Plonk implements PlonkInterface {

    /**
     * Laravel application
     * 
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new Building instance
     * 
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct(\Illuminate\Foundation\Application $app)
    {
        $this->app = $app;
    }

    public function validate($plonkJson)
    {
        if(empty($plonkJson))
        {
            return false;
        }

        if(!is_json($plonkJson))
        {
            return false;
        }

        return true;
    }

    public function smallest($plonkJson)
    {
        if(!$this->validate($plonkJson))
        {
            return '';
        }

        $base = rtrim(config('plonk.output.paths.base'), '/');
        $plonk = json_decode($plonkJson);
        $width = PHP_INT_MAX;

        foreach($plonk->variations as $key => $value)
        {
            if($value->width < $width)
            {
                $width = $value->width;
                $select = $value;
            }
        }

        if(is_null($select))
        {
            return '';
        }

        return implode('/', [$base, str_limit($plonk->hash, 4), $plonk->hash.'-'.$select->name.'.'.$plonk->extension]);
    }

    public function largest($plonkJson)
    {
        if(!$this->validate($plonkJson))
        {
            return '';
        }

        $base = rtrim(config('plonk.output.paths.base'), '/');
        $plonk = json_decode($plonkJson);
        $width = 0;

        foreach($plonk->variations as $key => $value)
        {
            if($value->width > $width)
            {
                $width = $value->width;
                $select = $value;
            }
        }

        if(is_null($select))
        {
            return '';
        }

        return implode('/', [$base, str_limit($plonk->hash, 4), $plonk->hash.'-'.$select->name.'.'.$plonk->extension]);
    }
}