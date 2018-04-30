<?php

namespace Metrique\Plonk;

use Illuminate\Contracts\View\View;
use Metrique\Plonk\Repositories\PlonkInterface as Plonk;

class PlonkViewComposer
{
    /**
     * The plonk repository implementation.
     *
     * @var Plonk
     */
    protected $plonk;

    /**
     * Create a new profile composer.
     *
     * @param  UserRepository  $users
     * @return void
     */
    public function __construct(Plonk $plonk)
    {
        // Dependencies automatically resolved by service container...
        $this->plonk = $plonk;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('plonk', $this->plonk);
    }
}
