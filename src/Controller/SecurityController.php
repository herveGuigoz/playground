<?php

namespace App\Controller;

use App\Security\AppleAuthenticator;
use App\Security\GoogleAuthenticator;
use Symfony\Component\HttpFoundation\Response;

class SecurityController extends AbstractController
{
    public function googleConnectAction(?string $token = null): Response
    {
        if (!$token) {
            return new Response('No token provided', Response::HTTP_BAD_REQUEST);
        }

        $authenticator = new GoogleAuthenticator();

        $user = $authenticator->authenticate($token);

        return $this->json(['user' => $user]);
    }

    public function appleConnectAction(
        ?string $token = null,
        ?string $platform = null,
        ?string $firstName = null,
        ?string $lastName = null
    ): Response {
        if (!$token || !$platform) {
            return new Response('Invalid Arguments', Response::HTTP_BAD_REQUEST);
        }

        $authenticator = new AppleAuthenticator($platform);

        $user = $authenticator->authenticate($token);

        return $this->json([
            'user' => $user,
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }
}
