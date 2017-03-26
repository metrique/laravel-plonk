<?php

namespace Metrique\Plonk\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Metrique\Plonk\Repositories\HookInterface as Hook;

class PlonkBaseController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Data for views.
     * @var array
     */
    protected $viewData = [];

    /**
     * List of views used.
     *
     * @var array
     */
    protected $views = [];

    /**
     * List of routes used
     * @var array
     */
    protected $routes = [];

    public function __construct(Hook $hook)
    {
        $this->viewData = [
            'routes' => $this->routes,
            'views' => $this->views,
            'data' => [],
        ];

        $hook->hook($this);
    }

    /**
     * Merges default view data with new view data.
     * @param  array $data
     * @return array
     */
    public function mergeViewData($data)
    {
        return $this->viewData = array_merge_recursive($this->viewData, $data);
    }

    /**
     * Helper to include view data.
     */
    public function viewWithData($view)
    {
        return view($view)->with($this->viewData);
    }
}
