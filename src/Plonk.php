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

    public $plonk;

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
        $this->plonk = null;
        
        if(empty($plonkJson))
        {
            return false;
        }

        if(!is_json($plonkJson))
        {
            return false;
        }

        $this->plonk = json_decode($plonkJson);

        if(json_last_error() != JSON_ERROR_NONE)
        {
            return false;
        }

        return true;
    }

    public function smallest($plonkJson = null)
    {
        if(!$this->validate($plonkJson))
        {
            return '';
        }

        $base = rtrim(config('plonk.output.paths.base'), '/');
        $width = PHP_INT_MAX;

        foreach($this->plonk->variations as $key => $value)
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

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function largest($plonkJson = null)
    {
        if(!$this->validate($plonkJson))
        {
            return '';
        }

        $base = rtrim(config('plonk.output.paths.base'), '/');
        $width = 0;

        foreach($this->plonk->variations as $key => $value)
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

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function alt($plonkJson = null)
    {
        if(!$this->validate($plonkJson))
        {
            return '';
        }

        return $this->plonk->alt;
    }
}