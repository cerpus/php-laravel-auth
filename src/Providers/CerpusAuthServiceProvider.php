<?php

namespace Cerpus\LaravelAuth\Providers;

use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Support\ServiceProvider;

class CerpusAuthServiceProvider extends ServiceProvider {
    public function boot() {
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }

        $this->publishes([
            // deprecated - remove together with old css dir
            dirname(dirname(__DIR__)).'/assets/css' => public_path('resources/reactive-login/static/css'),
            // deprecated - remove together with old js dir
            dirname(dirname(__DIR__)).'/assets/js' => public_path('resources/reactive-login/static/js'),
            // deprecated - remove together with old media dir
            dirname(dirname(__DIR__)).'/assets/media' => public_path('resources/reactive-login/static/media'),

            dirname(dirname(__DIR__)).'/assets/static' => public_path('resources/reactive-login/static'),
        ], 'public');
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../Config/cerpusauth.php', 'cerpusauth');

        $this->app->bind(CerpusAuthService::class, function ($app) {
            return new CerpusAuthService();
        });
    }
}
