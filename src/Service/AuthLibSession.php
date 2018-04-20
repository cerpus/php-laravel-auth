<?php

namespace Cerpus\LaravelAuth\Service;


use Cerpus\AuthCore\SessionInterface;

class AuthLibSession implements SessionInterface {

    public function put($key, $value) {
        session([$key => $value]);
    }

    public function remove($key) {
        session([$key => null]);
    }

    public function exists($key): bool {
        return session($key) ? true : false;
    }

    public function get($key) {
        return session($key);
    }
}