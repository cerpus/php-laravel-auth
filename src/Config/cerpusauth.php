<?php

return (function () {
    $jwtContextNames = array_filter(
        array_map(
            function ($context) {
                return trim($context);
            },
            explode(',', env('CERPUS_JWT_CONTEXTS', ''))
        ), function ($context) {
            return $context ? TRUE : FALSE;
        }
    );
    $jwtContexts = [];
    foreach ($jwtContextNames as $name) {
        $keyPrefix = 'CERPUS_JWT_' . strtoupper($name);
        $keys = [
            'pubkey' => $keyPrefix . '_PUBKEY',
            'privkey' => $keyPrefix . '_PRIVKEY',
            'type' => $keyPrefix . '_TYPE',
            'expiration-leeway-seconds' => $keyPrefix . '_EXPIRATION_LEEWAY_SECONDS'
        ];
        $config = ['name' => $name];
        foreach ($keys as $key => $env) {
            $config[$key] = env($env, NULL);
        }
        $jwtContexts[] = $config;
    }

    return [
        'server' => env('CERPUS_AUTH_SERVER', 'http://localhost'),
        'user' => env('CERPUS_AUTH_USER', 'cerpusauth-default-user-changeme'),
        'secret' => env('CERPUS_AUTH_SECRET',
            'cerpusauth-default-key-changeme'),
        'jwt-contexts' => $jwtContexts,
        'assetPath' => 'resources/reactive-login/static',
        'css' => 'css/main.a2d6fe11.css',
        'js' => 'js/main.15ac8a1f.js'
    ];
})();
