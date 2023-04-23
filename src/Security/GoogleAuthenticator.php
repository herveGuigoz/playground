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
            'redirectUri'  => GOOGLE_REDIRECT_URI,
        ]);
    }

    /**
     * Validate access token and retrieve user's information from Google API
     * @param string $token Google's access token
     * @return array User's information
     */
    public function authenticate(string $token): array
    {
        $accessToken = new AccessToken(['access_token' => $token]);

        $googleUser = $this->provider->getResourceOwner($accessToken);

        return $googleUser->toArray();
    }
}
