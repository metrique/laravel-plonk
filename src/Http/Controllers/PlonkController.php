<?php

namespace Metrique\Plonk\Http\Controllers;

use Illuminate\Http\Request;

use Metrique\Plonk\Http\Controller;
use Metrique\Plonk\Exceptions\PlonkException;
use Metrique\Plonk\Helpers\FoundationPaginationPresenter;
use Metrique\Plonk\Http\Requests\PlonkStoreRequest;
use Metrique\Plonk\Http\Requests\PlonkUpdateRequest;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface as PlonkRepository;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface as PlonkStoreRepository;

class PlonkController extends Controller
{
    protected $views = [
        'index' => 'metrique-plonk::index',
        'create' => 'metrique-plonk::create',
        'show' => 'metrique-plonk::show',
        'edit' => 'metrique-plonk::edit',
        'destroy' => 'metrique-plonk::destroy',
    ];

    protected $routes = [

    ];

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(PlonkRepository $plonk, Request $request)
    {
        $pagination = $plonk->paginateWithVariation(config('plonk.paginate.items'));
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

        flash()->success('Your image has uploaded successfully.'); // Replace with lang?

        return redirect()->route('plonk.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, PlonkRepository $plonk)
    {
        return view($this->views['show'])->with([
            'asset' => $plonk->findWithVariation($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, PlonkRepository $plonk)
    {
        return view($this->views['edit'])->with([
            'asset' => $plonk->findWithVariation($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(PlonkUpdateRequest $request, $id, PlonkRepository $plonk)
    {
        try {
            $plonk->update($id, [
                'title' => $request->input('title'),
                'alt' => $request->input('alt'),
            ]);
        } catch (PlonkException $e) {
            back()->withInput();
        }

        flash()->success('You have edited the image details successfully.');
        return redirect()->route('plonk.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, PlonkRepository $plonk)
    {
        try {
            $plonk->unpublish($id);
        } catch (PlonkException $e) {
            back()->withInput();
        }

        flash()->success('You have removed the image successfully.');
        return redirect()->route('plonk.index');
    }
}
