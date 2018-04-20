<?php

namespace Cerpus\LaravelAuth\Providers;

use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Support\ServiceProvider;

class CerpusAuthServiceProvider extends ServiceProvider {
    public function boot() {
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }
    }

    public function register() {
        $this->mergeConfigFrom(__DIR__.'/../Config/cerpusauth.php', 'cerpusauth');

        $this->app->bind(CerpusAuthService::class, function ($app) {
            return new CerpusAuthService();
        });
    }
}