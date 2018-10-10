<?php

namespace Cerpus\LaravelAuth\Controllers;


use Cerpus\AuthCore\AuthenticationHandler;
use Cerpus\LaravelAuth\Service\CerpusAuthService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class Oauth2ReturnController extends Controller {
    public function returnEndpoint(Request $request) {
        $params = $request->all();

        $authenticationHandler = App::make(AuthenticationHandler::class);
        return App::make(CerpusAuthService::class)->returnEndpoint($params)->handle($this->constructReturnUrl($request, '/oauth2/return'), $authenticationHandler);
    }
    public function codeJwtEndpoint(Request $request) {
        $code = $request->post('code', null);
        if ($code === null || !$code) {
            abort(400, "Missing code parameter");
        }
        /** @var CerpusAuthService $cerpusAuthService */
        $cerpusAuthService = App::make(CerpusAuthService::class);
        $accessTokenRequest = $cerpusAuthService->getAuthorizationCodeTokenRequest($this->constructReturnUrl($request, '/oauth2/return'), $code);
        $accessTokenResponse = $accessTokenRequest->execute();
        if (!$accessTokenResponse || !$accessTokenResponse->access_token) {
            abort(403, "Code not valid or failed to retrieve access token");
        }
        $jwt = $cerpusAuthService->getJwtService()->getJwtFromAccessToken($accessTokenResponse->access_token);
        if (!$jwt) {
            abort(403, "Failed to create JWT");
        }
        return response()->json(['jwt' => $jwt]);
    }

    private function constructReturnUrl(Request $request, $path)
    {
        $url = parse_url($request->url());
        $returnUrl = $url['scheme'] . '://' . $url['host'] . $path;
        return $returnUrl;
    }
}