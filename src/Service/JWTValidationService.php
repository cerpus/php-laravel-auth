<?php

namespace Cerpus\LaravelAuth\Service;


use Cerpus\JWTSupport\JWTVerifier;
use Cerpus\LaravelAuth\ValidJWT;

class JWTValidationService {
    protected function decodeBase64($base64) {
        /*
         * URL-safe base64
         */
        $base64 = str_replace('-', '+', $base64);
        $base64 = str_replace('_', '/', $base64);

        $decoded = base64_decode($base64);

        return $decoded;
    }
    public function isJwt($jwt) {
        $parts = explode('.', $jwt);
        if (is_array($parts)) {
            try {
                $headerB64 = $parts[0];
                if (!$headerB64) {
                    return false;
                }
                $headerStr = $this->decodeBase64($headerB64);
                $header = json_decode($headerStr, FALSE);
                if (isset($header) && $header && isset($header->alg) && isset($header->typ) && $header->typ === 'JWT') {
                    return TRUE;
                }
            } catch (\Exception $e) {
            }
            return false;
        }
    }
    public function validateJwt($jwt) {
        $jwtContexts = config('cerpusauth.jwt-contexts');
        $pubKeys = [];
        $config = [];
        foreach ($jwtContexts as $jwtContext) {
            $pubKeys[$jwtContext['name']] = $jwtContext['pubkey'];
            $config[$jwtContext['name']] = $jwtContext;
        }
        $verifier = new JWTVerifier($pubKeys);
        foreach ($jwtContexts as $jwtContext) {
            $name = $jwtContext['name'];
            $validator = $verifier->getVerifierByName($name);
            if (isset($jwtContext['expiration-leeway-seconds']) && $jwtContext['expiration-leeway-seconds']) {
                $validator->setLeeway($jwtContext['expiration-leeway-seconds']);
            }
        }
        $validated = $verifier->verify($jwt);
        if ($validated !== null) {
            $name = $validated->getName();
            $payload = $validated->getJwt();
            $jwtContext = $config[$name];
            $type = $jwtContext['type'];
            return new ValidJWT($name, $jwt, $payload, $type);
        }
        return null;
    }
}