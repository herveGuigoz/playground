<?php

namespace App\Controller;

use App\Security\AppleAuthenticator;
use App\Security\GoogleAuthenticator;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    /**
     * OAuth2 Google Connect
     * @param string|null $token Google's access token
     * @return Response
     */
    public function googleConnectAction(?string $token = null): Response
    {
        if (!$token) {
            return new Response('No token provided', Response::HTTP_BAD_REQUEST);
        }

        $authenticator = new GoogleAuthenticator();

        $user = $authenticator->authenticate($token);

        return $this->json([
            'user' => $user,
        ]);
    }

    /**
     * OAuth2 Apple Connect
     * @param string|null $token Apple's authorization code
     * @param string|null $platform Application platform (ios or android)
     * @return Response
     */
    public function appleConnectAction(?string $token = null, ?string $platform = null): Response
    {
        if (!$token || !$platform) {
            return new Response('Invalid Arguments', Response::HTTP_BAD_REQUEST);
        }

        $authenticator = new AppleAuthenticator($platform);

        $user = $authenticator->authenticate($token);

        return $this->json([
            'user' => $user,
        ]);
    }

    /**
     * The callback route used for Android, which will send the callback parameters from Apple into the Android app.
     * his is done using a deeplink, which will cause the Chrome Custom Tab to be dismissed and providing the
     * parameters from Apple back to the app.
     */
    public function appleConnectRedirectAction(): Response
    {
        // This is the deeplink that will be used to send the parameters back to the Android app.
        // The deeplink will be in the format of :
        // intent://callback?<REQUEST_PARAMS>Intent;package=<ANDROID_BUNDLE>;scheme=signinwithapple
        return $this->redirect('TODO', status: 307);
    }
}
