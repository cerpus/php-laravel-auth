<?php

namespace Cerpus\LaravelAuth\Controllers;


use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class LoginInvokeController extends Controller {
    public function getLoginData(Request $request) {
        $redirect = $request->get("redirect", null);
        $abort = $request->get("abort", null);
        $state = sha1(mt_rand().mt_rand());
        /** @var $cerpusAuthService CerpusAuthService */
        $cerpusAuthService = App::make(CerpusAuthService::class);
        if (!($cerpusAuthService instanceof CerpusAuthService)) {
            throw new \Exception("Class type exception: Required CerpusAuthService");
        }
        return response()->json($cerpusAuthService->getAuthorizeRequest()->executeApi($redirect, $abort, $state));
    }
}