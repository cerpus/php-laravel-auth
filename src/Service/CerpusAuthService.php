<?php

namespace Cerpus\LaravelAuth\Service;

use Cerpus\AuthCore\AuthCoreIntegration;
use Cerpus\AuthCore\AccessTokenManager;
use Cerpus\AuthCore\AuthorizationCodeTokenRequest;
use Cerpus\AuthCore\AuthorizeRequest;
use Cerpus\AuthCore\AuthProfileService;
use Cerpus\AuthCore\AuthServiceConfig;
use Cerpus\AuthCore\CheckTokenRequest;
use Cerpus\AuthCore\ClientCredentialsTokenRequest;
use Cerpus\AuthCore\CreateUserApiService;
use Cerpus\AuthCore\IdentityRequest;
use Cerpus\AuthCore\Oauth2Flow;
use Cerpus\AuthCore\TokenResponse;
use Cerpus\AuthCore\AuthLocalesService;
use Cerpus\LaravelAuth\AssetManifest;

class CerpusAuthService {
    private $config = null;
    private $authCoreIntegration = null;
    private $accessTokenManager = null;

    public function getConfig(): AuthServiceConfig {
        if ($this->config === null) {
            $this->config = (new AuthServiceConfig())
                ->setUrl(config('cerpusauth.server'))
                ->setClientId(config('cerpusauth.user'))
                ->setSecret(config('cerpusauth.secret'));
        }
        return $this->config;
    }

    public function getAuthCoreIntegration(): AuthCoreIntegration {
        if ($this->authCoreIntegration === null) {
            $this->authCoreIntegration = new AuthLibPlatformIntegration();
        }
        return $this->authCoreIntegration;
    }

    public function getAccessTokenManager(): AccessTokenManager {
        if ($this->accessTokenManager === null) {
            $this->accessTokenManager = new AccessTokenManager($this->getConfig(), $this->getAuthCoreIntegration());
        }
        return $this->accessTokenManager;
    }

    public function getAuthorizeRequest(): AuthorizeRequest {
        return new AuthorizeRequest($this->getConfig());
    }

    public function getAuthorizationCodeTokenRequest($redirectUri, $authorizationCode): AuthorizationCodeTokenRequest {
        return new AuthorizationCodeTokenRequest($this->getConfig(), $redirectUri, $authorizationCode);
    }

    public function getClientCredentialsTokenRequest(): ClientCredentialsTokenRequest {
        return new ClientCredentialsTokenRequest($this->getConfig());
    }

    public function getCheckTokenRequest(string $accessToken): CheckTokenRequest {
        return new CheckTokenRequest($this->getConfig(), $accessToken);
    }

    public function getIdentityRequest(TokenResponse $accessToken): IdentityRequest {
        return new IdentityRequest($this->getConfig(), $accessToken);
    }

    public function startFlow(): Oauth2Flow {
        return Oauth2Flow::startFlow($this->getConfig(), $this->getAuthCoreIntegration());
    }

    public function returnEndpoint(array $params): Oauth2Flow {
        return Oauth2Flow::returnEndpoint($this->getConfig(), $this->getAuthCoreIntegration(), $params);
    }

    public function getAuthLocalesService(): AuthLocalesService {
        return new AuthLocalesService($this->getConfig());
    }

    public function getAuthProfileService(): AuthProfileService {
        return new AuthProfileService($this->getConfig(), $this->getAuthCoreIntegration());
    }

    public function getCreateUserApiService(): CreateUserApiService {
        return new CreateUserApiService($this->getConfig());
    }

    public function getJwtService(): \Cerpus\AuthCore\JWTService {
        return new \Cerpus\AuthCore\JWTService($this->getConfig(), $this->getAuthCoreIntegration());
    }

    /**
     * @deprecated Use the getLoginAssetManifest() instead. It'll have more recent css and js. It will return multiple instances of css and js that need to be added to the head.
     */
    public function getCssUrl() {
        return url(config('cerpusauth.assetPath').'/'.config('cerpusauth.css'));
    }

    /**
     * @deprecated Use the getLoginAssetManifest() instead. It'll have more recent css and js. It will return multiple instances of css and js that need to be added to the head.
     */
    public function getJsUrl() {
        return url(config('cerpusauth.assetPath').'/'.config('cerpusauth.js'));
    }

    public function getLoginAssetManifest(): AssetManifest {
        return new AssetManifest(config('cerpusauth.assetManifest'), config('cerpusauth.assetManifestRootPath'), '/'.config('cerpusauth.assetPath').'/');
    }
}
