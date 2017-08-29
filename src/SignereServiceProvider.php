<?php

namespace Sausin\Signere;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Events\Dispatcher;

class SignereServiceProvider extends ServiceProvider
{
    use EventMap;

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->registerEvents();
        $this->registerRoutes();
        $this->defineAssetPublishing();
    }

    /**
     * Register the Signere job events.
     *
     * @return void
     */
    protected function registerEvents()
    {
        $events = $this->app->make(Dispatcher::class);

        foreach ($this->events as $event => $listeners) {
            foreach ($listeners as $listener) {
                $events->listen($event, $listener);
            }
        }
    }

    /**
     * Register the Signere routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::group([
            'prefix' => 'signere',
            'namespace' => 'Sausin\Signere\Http\Controllers',
            'middleware' => 'api',
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Define the asset publishing configuration.
     *
     * @return void
     */
    public function defineAssetPublishing()
    {
        $this->publishes([
            SIGNERE_PATH.'/public' => public_path('vendor/signere'),
        ], 'signere-assets');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (! defined('SIGNERE_PATH')) {
            define('SIGNERE_PATH', realpath(__DIR__.'/../'));
        }

        $this->configure();
        $this->registerCommands();
        $this->registerBindings();
        $this->offerPublishing();
    }

    /**
     * Setup the configuration for Signere.
     *
     * @return void
     */
    protected function configure()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/signere.php',
            'signere'
        );
    }

    /**
     * Registers the commands available with this package.
     *
     * @return void
     */
    protected function registerCommands()
    {
        if ($this->app->runningInConsole()) {
            // register the console command
            $this->commands([Console\RenewCommand::class]);
        }
    }

    /**
     * Register the bindings.
     *
     * @return void
     */
    protected function registerBindings()
    {
        $this->app->bind('signere-headers', function () {
            return new Headers(config());
        });
        $this->app->alias('signere-headers', Facades\SignereHeaders::class);

        $classes = [
            'ApiKey',
            'Document',
            'DocumentConvert',
            'DocumentFile',
            'DocumentJob',
            'DocumentProvider',
            'Events',
            'ExternalLogin',
            'ExternalSign',
            'Form',
            'Invoice',
            'Message',
            'Receiver',
            'RequestId',
            'Statistics',
            'Status',
        ];

        // get the environment of the application
        $env = is_null(config('signere.mode')) ? $this->app->environment() : config('signere.mode');

        foreach ($classes as $class) {
            $this->app->when('Sausin\Signere\\'.$class)
                        ->needs('$environment')
                        ->give($env);
        }
    }

    /**
     * Setup the resource publishing groups for Signere.
     *
     * @return void
     */
    protected function offerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/signere.php' => config_path('signere.php'),
            ], 'signere-config');
        }
    }
}
