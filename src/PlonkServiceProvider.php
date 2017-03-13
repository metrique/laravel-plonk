<?php

namespace Metrique\Plonk;

use Illuminate\Support\ServiceProvider;
use Metrique\Plonk\Commands\PlonkMigrationsCommand;
use Metrique\Plonk\Commands\PlonkBulkCommand;
use Metrique\Plonk\Repositories\Contracts\HookRepositoryInterface;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface;
use Metrique\Plonk\Repositories\HookRepository;
use Metrique\Plonk\Repositories\PlonkRepositoryEloquent;
use Metrique\Plonk\Repositories\PlonkStoreRepositoryEloquent;
use Sofa\Eloquence\ServiceProvider as EloquenceServiceProvider;

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
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Repositories
        $this->registerHookRepository();
        $this->registerPlonkIndexRepository();
        $this->registerPlonkStoreRepository();

        // Commands
        $this->registerCommands();

        // Eloquence
        $this->registerEloquence();
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
            require __DIR__.'/Routes/api.php';
            require __DIR__.'/Routes/web.php';
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

    public function registerEloquence()
    {
        $this->app->register(EloquenceServiceProvider::class);
    }

    protected function registerHookRepository()
    {
        $this->app->bind(
            HookRepositoryInterface::class,
            HookRepository::class
        );
    }

    public function registerPlonkIndexRepository()
    {
        $this->app->bind(
            PlonkRepositoryInterface::class,
            PlonkRepositoryEloquent::class
        );
    }

    public function registerPlonkStoreRepository()
    {
        $this->app->bind(
            PlonkStoreRepositoryInterface::class,
            PlonkStoreRepositoryEloquent::class
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
