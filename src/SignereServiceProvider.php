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
            'middleware' => 'web',
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

        $this->registerFacades();
        $this->configure();
        $this->offerPublishing();
    }

    protected function registerFacades()
    {
        $this->app->bind('signere-headers', function () {
            return new Headers(config());
        });
        $this->app->bind('signere-status', function ($app) {
            return new Status($app->make('GuzzleHttp\Client'), $app->make(Headers::class), config());
        });
        $this->app->bind('signere-events', function ($app) {
            return new Events($app->make('GuzzleHttp\Client'), $app->make(Headers::class));
        });
        $this->app->bind('signere-api-key', function ($app) {
            return new ApiKey($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $app->environment());
        });

        $this->app->alias('signere-headers', Facades\SignereHeaders::class);
        $this->app->alias('signere-status', Facades\SignereStatus::class);
        $this->app->alias('signere-events', Facades\SignereStatus::class);
        $this->app->alias('signere-api-key', Facades\SignereApiKey::class);
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
