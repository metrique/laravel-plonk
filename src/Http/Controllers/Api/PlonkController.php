<?php

namespace Metrique\Plonk\Http\Controllers\Api;

use Illuminate\Http\Request;

use Metrique\Plonk\Http\Controllers\PlonkBaseController;
use Metrique\Plonk\Http\Requests\PlonkStoreRequest;
use Metrique\Plonk\Repositories\PlonkInterface as Plonk;
use Metrique\Plonk\Repositories\PlonkStoreInterface as PlonkStore;

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

    public function store(PlonkStoreRequest $request, PlonkStore $plonk)
    {
        $plonk->store();

        return response()->json(true);
    }
}
