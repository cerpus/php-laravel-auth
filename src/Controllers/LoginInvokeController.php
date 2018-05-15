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
        $toSignup = $request->get("signup", false);
        $username = $request->get("username", false);
        $emailId = $request->get("emailId", null);
        $emailCode = $request->get("emailCode", null);
        $language = $request->get("language", null);
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
        $authorizeRequest = $cerpusAuthService->getAuthorizeRequest()
            ->setToSignup($toSignup ? true : false);
        if ($username) {
            $authorizeRequest = $authorizeRequest->setUsername($username);
        }
        if ($emailId && $emailCode) {
            $authorizeRequest = $authorizeRequest->setEmailId($emailId)->setEmailCode($emailCode);
        }
        if ($language) {
            $authorizeRequest = $authorizeRequest->setLanguage($language);
        }
        return response()->json($authorizeRequest->executeApi($returnUrl, $abort, $legacyAuthorizeParams['state']));
    }
}