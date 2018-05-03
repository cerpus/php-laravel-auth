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
        /** @var $cerpusAuthService CerpusAuthService */
        $cerpusAuthService = App::make(CerpusAuthService::class);
        if (!($cerpusAuthService instanceof CerpusAuthService)) {
            throw new \Exception("Class type exception: Required CerpusAuthService");
        }
        $flow = $cerpusAuthService->startFlow()
            ->setSuccessUrl($redirect)
            ->setFailureUrl($abort);
        $returnUrl = route('oauth2.return');
        $legacyAuthorizeUrl = $flow->authorizeUrl($returnUrl);
        $legacyAuthorizeQuery = parse_url($legacyAuthorizeUrl, PHP_URL_QUERY);
        parse_str($legacyAuthorizeQuery, $legacyAuthorizeParams);
        return response()->json($cerpusAuthService->getAuthorizeRequest()->executeApi($returnUrl, $abort, $legacyAuthorizeParams['state']));
    }
}