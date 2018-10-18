<?php

namespace Cerpus\LaravelAuth\Service;


use Cerpus\JWTSupport\JWTSigner;

class JWTGeneratorService {

    /**
     * @param string $contextName
     * @param array $payload
     *
     * @return string
     * @throws \Exception
     */
    public function signJwt(string $contextName, array $payload)/*:? string*/ {
        $jwtContexts = config('cerpusauth.jwt-contexts');
        $privKeys = [];
        $config = [];
        foreach ($jwtContexts as $jwtContext) {
            if (isset($jwtContext['privkey'])) {
                $privKeys[$jwtContext['name']] = $jwtContext['privkey'];
                $config[$jwtContext['name']] = $jwtContext;
            }
        }

        if (isset($privKeys[$contextName])) {
            $privKey = $privKeys[$contextName];

            $signer = new JWTSigner($privKey);

            return $signer->sign($payload);
        } else {
            throw new \Exception("Context not found ".$contextName);
        }
    }
}