<?php

namespace Cerpus\LaravelAuth\Service;


use Cerpus\JWTSupport\JWTSigner;

class JWTGeneratorService {
    public function signJwt($contextName, $payload) {
        $jwtContexts = config('cerpusauth.jwt-contexts');
        $privKeys = [];
        $config = [];
        foreach ($jwtContexts as $jwtContext) {
            $privKeys[$jwtContext['name']] = $jwtContext['privkey'];
            $config[$jwtContext['name']] = $jwtContext;
        }

        $privKey = $privKeys[$contextName];

        $signer = new JWTSigner($privKey);

        return $signer->sign($payload);
    }
}