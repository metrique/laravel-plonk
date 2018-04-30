<?php

namespace Metrique\Plonk\Http\Controllers\Api;

use Illuminate\Http\Request;

use Metrique\Plonk\Http\Controllers\PlonkBaseController;
use Metrique\Plonk\Repositories\PlonkInterface as Plonk;

class PlonkController extends PlonkBaseController
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Plonk $plonk, Request $request)
    {
        $assets = $plonk
            ->allFiltered()
            ->paginate(10)
            ->appends($plonk->filterRequest()->toArray());

        return $assets;
    }
}
