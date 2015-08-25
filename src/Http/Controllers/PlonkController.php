<?php

namespace Metrique\Plonk\Http\Controllers;

use Illuminate\Http\Request;

use TG\Http\Requests;
use TG\Http\Controllers\Controller;
use Metrique\Plonk\Exceptions\PlonkException;
use Metrique\Plonk\Helpers\FoundationPaginationPresenter;
use Metrique\Plonk\Http\Requests\PlonkStoreRequest;
use Metrique\Plonk\Repositories\Contracts\PlonkIndexRepositoryInterface as PlonkIndexRepository;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface as PlonkStoreRepository;

class PlonkController extends Controller
{
    protected $views = [
        'index' => 'metrique-plonk::index',
        'create' => 'metrique-plonk::create',
        'store' => 'metrique-plonk::store',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PlonkIndexRepository $plonk, Request $request)
    {
        $pagination = $plonk->paginate(config('plonk.paginate.items'));
        $querystring = array_only($request->input(), 'filter');

        // $pagination->append(PlonkQueryString::get(['filter', 'ratio']));
        return view($this->views['index'])->with([
            'assets' => $pagination,
            'pagination' => FoundationPaginationPresenter::present($pagination->appends($querystring)),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(PlonkStoreRepository $plonk)
    {
        //
        return view($this->views['create'])->with('ratios', $plonk->getCropRatios());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(PlonkStoreRequest $request, PlonkStoreRepository $plonk)
    {
        // Test if the file upload validates...
        try
        {
            $plonk->store();
        }

        catch (PlonkException $e)
        {
            return back()->withInput();
        }

        return redirect('plonk.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
