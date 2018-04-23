<?php

namespace Cerpus\LaravelAuth\Controllers;


use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

class JWTController extends Controller {
    public function getJwt() {
        if (Auth::check()) {
            $jwt = App::make(CerpusAuthService::class)->getJwtService()->getJwt();

            if ($jwt) {
                return ['token' => $jwt];
            }

            App::abort(503);
        } else {
            App::abort(403);
        }
    }
}