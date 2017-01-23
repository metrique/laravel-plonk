<?php

namespace Metrique\Plonk;

use Illuminate\Support\ServiceProvider;
use Metrique\Plonk\Commands\PlonkMigrationsCommand;
use Metrique\Plonk\Repositories\Contracts\PlonkRepositoryInterface;
use Metrique\Plonk\Repositories\Contracts\PlonkStoreRepositoryInterface;
use Metrique\Plonk\Repositories\PlonkRepositoryEloquent;
use Metrique\Plonk\Repositories\PlonkStoreRepositoryEloquent;
use Sofa\Eloquence\ServiceProvider as EloquenceServiceProvider;

class PlonkServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/Resources/views/', 'metrique-plonk');

        // Config
        $this->publishes([
            __DIR__.'/Resources/config/plonk.php' => config_path('plonk.php')
        ], 'plonk-config');

        // Views

        // Commands
        $this->commands('command.metrique.migrate-plonk');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerEloquence();

        $this->registerConfig();
        $this->registerCommands();

        $this->registerPlonkIndexRepository();
        $this->registerPlonkStoreRepository();
    }

    public function registerEloquence()
    {
        $this->app->register(EloquenceServiceProvider::class);
    }

    /**
     * Register the PlonkIndexRepository binding
     * @return void
     */
    public function registerPlonkIndexRepository()
    {
        $this->app->bind(
            PlonkRepositoryInterface::class,
            PlonkRepositoryEloquent::class
        );
    }

    /**
     * Register the PlonkStoreRepository binding
     * @return void
     */
    public function registerPlonkStoreRepository()
    {
        $this->app->bind(
            PlonkStoreRepositoryInterface::class,
            PlonkStoreRepositoryEloquent::class
        );
    }

    /**
     * Reguster tge artisan command
     *
     * @return void
     */
    public function registerCommands()
    {
        $this->app->singleton('command.metrique.migrate-plonk', function ($app) {
            return new PlonkMigrationsCommand;
        });
    }

    /**
     * Merge config
     * @return void
     */
    public function registerConfig()
    {
        $this->mergeConfigFrom(
            __DIR__.'/Resources/config/plonk.php', 'plonk'
        );
    }
}
