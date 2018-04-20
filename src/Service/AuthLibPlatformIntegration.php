<?php

namespace Cerpus\LaravelAuth\Service;


use Cerpus\AuthCore\AuthCoreIntegration;
use Cerpus\AuthCore\SessionInterface;

class AuthLibPlatformIntegration implements AuthCoreIntegration {
    private $session = null;

    public function session(): SessionInterface {
        if ($this->session === null) {
            $this->session = new AuthLibSession();
        }
        return $this->session;
    }
}