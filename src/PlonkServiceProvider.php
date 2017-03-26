<?php

namespace Metrique\Plonk;

use Illuminate\Support\ServiceProvider;
use Metrique\Plonk\Commands\PlonkMigrationsCommand;
use Metrique\Plonk\Commands\PlonkBulkCommand;
use Metrique\Plonk\Repositories\HookInterface;
use Metrique\Plonk\Repositories\PlonkInterface;
use Metrique\Plonk\Repositories\PlonkStoreInterface;
use Metrique\Plonk\Repositories\Hook;
use Metrique\Plonk\Repositories\Plonk;
use Metrique\Plonk\Repositories\PlonkStore;
use Metrique\Plonk\PlonkViewComposer;

class PlonkServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function __construct($app)
    {
        parent::__construct($app);
    }

    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->bootCommands();
        $this->bootConfig();
        $this->bootMigrations();
        $this->bootRoutes();
        $this->bootViews();
        
        // View composer
        view()->composer('*', PlonkViewComposer::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Repositories
        $this->registerHook();
        $this->registerPlonk();
        $this->registerPlonkStore();

        // Commands
        $this->registerCommands();
    }

    public function bootCommands()
    {
        $this->commands('command.metrique.plonk-bulk');
    }

    public function bootConfig()
    {
        $this->publishes([
            __DIR__.'/Resources/config/plonk.php' => config_path('plonk.php'),
        ], 'laravel-plonk');

        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/plonk.php',
            'plonk'
        );
    }

    protected function bootMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/migrations');
    }

    public function bootRoutes()
    {
        if (! $this->app->routesAreCached()) {
            // TODO
            if (config('plonk.routes.api')) {
                require __DIR__.'/Routes/api.php';
            }
            
            if (config('plonk.routes.web')) {
                require __DIR__.'/Routes/web.php';
            }
        }
    }

    public function bootViews()
    {
        $views = __DIR__ . '/Resources/views/';
        $this->loadViewsFrom($views, 'laravel-plonk');

        $this->publishes([
            __DIR__.'/Resources/views' => resource_path('views/vendor/laravel-plonk'),
        ], 'laravel-plonk');
    }

    protected function registerHook()
    {
        $this->app->bind(
            HookInterface::class,
            Hook::class
        );
    }

    public function registerPlonk()
    {
        $this->app->bind(
            PlonkInterface::class,
            Plonk::class
        );
    }

    public function registerPlonkStore()
    {
        $this->app->bind(
            PlonkStoreInterface::class,
            PlonkStore::class
        );
    }

    /**
     * Register the artisan commands.
     */
    public function registerCommands()
    {
        $this->app->singleton('command.metrique.plonk-bulk', function ($app) {
            return new PlonkBulkCommand();
        });
    }
}
