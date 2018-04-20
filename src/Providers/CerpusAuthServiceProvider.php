<?php

namespace Cerpus\LaravelAuth\Providers;

use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Support\ServiceProvider;

class CerpusAuthServiceProvider extends ServiceProvider {
    public function boot() {
        if (!$this->app->routesAreCached()) {
            require __DIR__.'/../routes.php';
        }

        $this->app->bind(CerpusAuthService::class, function ($app) {
            return new CerpusAuthService();
        });

        $this->mergeConfigFrom(__DIR__.'/../Config/cerpusauth.php', 'cerpusauth');
    }
}