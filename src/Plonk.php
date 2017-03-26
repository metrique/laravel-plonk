<?php

namespace Metrique\Plonk;

use Illuminate\Foundation\Application;
use Metrique\Plonk\Contracts\PlonkInterface;
use Stringy\Stringy;

class Plonk implements PlonkInterface
{

    /**
     * Laravel application
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    public $plonk;
    public $plonkArray;
    /**
     * Create a new Building instance
     *
     * @param \Illuminate\Foundation\Application $app
     * @return void
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function make($plonkJson)
    {
        $this->plonk = json_decode($plonkJson);
        $this->plonkArray = json_decode($plonkJson, true);

        if (json_last_error() != JSON_ERROR_NONE) {
            return false;
        }

        return true;
    }

    public function validate($plonkJson)
    {
        $this->plonk = null;

        if (empty($plonkJson)) {
            return false;
        }

        return $this->make($plonkJson);
    }

    public function byName($name, $plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        $base = rtrim(config('plonk.input.paths.base'), '/');
        $key = array_search($name, array_column($this->plonkArray['variations'], 'name'));
        $select = $this->plonk->variations[$key];

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function smallest($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        $base = rtrim(config('plonk.input.paths.base'), '/');
        $width = PHP_INT_MAX;

        foreach ($this->plonk->variations as $key => $value) {
            if ($value->width < $width) {
                $width = $value->width;
                $select = $value;
            }
        }

        if (is_null($select)) {
            return '';
        }

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function largest($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        $base = rtrim(config('plonk.input.paths.base'), '/');
        $width = 0;

        foreach ($this->plonk->variations as $key => $value) {
            if ($value->width > $width) {
                $width = $value->width;
                $select = $value;
            }
        }

        if (is_null($select)) {
            return '';
        }

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function original($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        $base = rtrim(config('plonk.input.paths.base'), '/');

        return implode('/', [$base, ltrim(config('plonk.input.paths.originals'), '/'), $this->plonk->hash.'.'.$this->plonk->extension]);
    }

    public function alt($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        return $this->plonk->alt;
    }
}
