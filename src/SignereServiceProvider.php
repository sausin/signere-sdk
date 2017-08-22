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

        // get the environment of the application
        $env = is_null(config('signere.mode')) ? $this->app->environment() : config('signere.mode');

        $this->app->bind('signere-api-key', function ($app) use ($env) {
            return new ApiKey($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        });
        // $this->app->bind('signere-document', function ($app) use ($env) {
        //     return new Document($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-document-convert', function ($app) use ($env) {
        //     return new DocumentConvert($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-document-file', function ($app) use ($env) {
        //     return new DocumentFile($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-document-job', function ($app) use ($env) {
        //     return new DocumentJob($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-document-provider', function ($app) use ($env) {
        //     return new DocumentProvider($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-events', function ($app) use ($env) {
        //     return new Events($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-external-login', function ($app) use ($env) {
        //     return new ExternalLogin($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-external-sign', function ($app) use ($env) {
        //     return new ExternalSign($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-form', function ($app) use ($env) {
        //     return new Form($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-invoice', function ($app) use ($env) {
        //     return new Invoice($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-message', function ($app) use ($env) {
        //     return new Message($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-receiver', function ($app) use ($env) {
        //     return new Receiver($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        // $this->app->bind('signere-statistics', function ($app) use ($env) {
        //     return new Statistics($app->make('GuzzleHttp\Client'), $app->make(Headers::class), $env);
        // });
        $this->app->bind('signere-status', function ($app) use ($env) {
            return new Status($app->make('GuzzleHttp\Client'), $app->make(Headers::class), config(), $env);
        });

        $this->app->alias('signere-headers', Facades\SignereHeaders::class);
        $this->app->alias('signere-api-key', Facades\SignereApiKey::class);
        // $this->app->alias('signere-document', Facades\SignereDocument::class);
        // $this->app->alias('signere-document-convert', Facades\SignereDocumentConvert::class);
        // $this->app->alias('signere-document-file', Facades\SignereDocumentFile::class);
        // $this->app->alias('signere-document-job', Facades\SignereDocumentJob::class);
        // $this->app->alias('signere-document-provider', Facades\SignereDocumentProvider::class);
        // $this->app->alias('signere-events', Facades\SignereEvents::class);
        // $this->app->alias('signere-external-login', Facades\SignereExternalLogin::class);
        // $this->app->alias('signere-external-sign', Facades\SignereExternalSign::class);
        // $this->app->alias('signere-form', Facades\SignereForm::class);
        // $this->app->alias('signere-invoice', Facades\SignereInvoice::class);
        // $this->app->alias('signere-message', Facades\SignereMessage::class);
        // $this->app->alias('signere-receiver', Facades\SignereReceiver::class);
        // $this->app->alias('signere-statistics', Facades\SignereStatistics::class);
        $this->app->alias('signere-status', Facades\SignereStatus::class);
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
