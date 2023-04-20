<?php

namespace App\Security;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Token\AccessToken;

class GoogleAuthenticator
{
    protected AbstractProvider $provider;

    public function __construct()
    {
        $this->provider = new Google([
            'clientId'     => GOOGLE_CLIENT_ID,
            'clientSecret' => GOOGLE_CLIENT_SECRET,
            'redirectUri'  => GOOGLE_REDIRECT_URI
        ]);
    }

    public function authenticate(string $token): array
    {
        $accessToken = new AccessToken(['access_token' => $token]);

        $googleUser = $this->provider->getResourceOwner($accessToken);

        return [
            'id' => $googleUser->getId(),
            'email' => $googleUser->getEmail(),
        ];
    }
}
