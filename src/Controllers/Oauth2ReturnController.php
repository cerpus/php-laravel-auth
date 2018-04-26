<?php

namespace Cerpus\LaravelAuth\Controllers;


use Cerpus\AuthCore\AuthenticationHandler;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class Oauth2ReturnController extends Controller {
    public function returnEndpoint(Request $request) {
        $params = $request->all();

        $authenticationHandler = App::make(AuthenticationHandler::class);
        return App::make(\Cerpus\LaravelAuth\Service\CerpusAuthService::class)->returnEndpoint($params)->handle($this->constructReturnUrl($request, '/oauth2/return'), $authenticationHandler);
    }

    private function constructReturnUrl(Request $request, $path)
    {
        $url = parse_url($request->url());
        $returnUrl = $url['scheme'] . '://' . $url['host'] . $path;
        return $returnUrl;
    }
}