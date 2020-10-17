<?php

namespace JeromeFitzpatrick\StartingPoint;

use Illuminate\Support\ServiceProvider;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJModel;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJRequest;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJResource;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJResponse;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJController;
use JeromeFitzpatrick\StartingPoint\Console\Commands\MakeJTransformer;

class StartingPointServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerResources();
        $this->registerPublishing();
        $this->registerCommands();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->configure();
    }

    /**
     * Setup the configuration for Cashier.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/starting-point.php', 'starting-point'
        );
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    protected function registerResources()
    {
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'starting-point');
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing()
    {
        $this->publishes([
            __DIR__.'/stubs/js/components' => resource_path('js/components'),
        ], 'jcommon');

        $this->publishes([
            __DIR__.'/stubs/js/JCommon' => resource_path('js/JCommon'),
        ], 'jcommon');

        $this->publishes([
            __DIR__.'/stubs/js/root' => resource_path('js'),
        ], 'jcommon');

        $this->publishes([
            __DIR__.'/stubs/js/approot' => app_path(),
        ], 'jcommon');
    }

    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeJController::class,
                MakeJModel::class,
                MakeJRequest::class,
                MakeJResource::class,
                MakeJResponse::class,
                MakeJTransformer::class,
            ]);
        }
    }

}
