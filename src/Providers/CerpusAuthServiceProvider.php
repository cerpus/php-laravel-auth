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
            dirname(dirname(__DIR__)).'/assets' => public_path('vendor/cerpus/laravel-auth'),
        ], 'public');
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../Config/cerpusauth.php', 'cerpusauth');

        $this->app->bind(CerpusAuthService::class, function ($app) {
            return new CerpusAuthService();
        });
    }
}