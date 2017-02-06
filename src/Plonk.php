<?php

namespace Metrique\Plonk;

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
    public function __construct(\Illuminate\Foundation\Application $app)
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

        $base = rtrim(config('plonk.output.paths.base'), '/');
        $key = array_search($name, array_column($this->plonkArray['variations'], 'name'));
        $select = $this->plonk->variations[$key];

        return implode('/', [$base, str_limit($this->plonk->hash, 4), $this->plonk->hash.'-'.$select->name.'.'.$this->plonk->extension]);
    }

    public function smallest($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        $base = rtrim(config('plonk.output.paths.base'), '/');
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

        $base = rtrim(config('plonk.output.paths.base'), '/');
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

        $base = rtrim(config('plonk.output.paths.base'), '/');

        return implode('/', [$base, ltrim(config('plonk.output.paths.originals'), '/'), $this->plonk->hash.'.'.$this->plonk->extension]);
    }

    public function alt($plonkJson = null)
    {
        if (!$this->validate($plonkJson)) {
            return '';
        }

        return $this->plonk->alt;
    }
}
