<?php

namespace App\Security;

use Exception;
use Firebase\JWT\JWT;

class AppleAccessToken
{
    protected string $accessToken;

    protected int $expiresIn;

    protected string $refreshToken;

    /**
     * The decoded id_token from Apple which contains the user’s identity information.
     */
    protected array $payload;

    public function __construct(array $keys, array $response)
    {
        $this->accessToken = $response['access_token'];
        $this->expiresIn = $response['expires_in'];
        $this->refreshToken = $response['refresh_token'];
        $idToken = $response['id_token'];

        $decoded = null;

        foreach ($keys as $key) {
            try {
                $decoded = JWT::decode($idToken, $key);
                break;
            } catch (\Exception $e) {
                continue;
            }
        }

        if (null === $decoded) {
            throw new Exception('Invalid token');
        }

        $this->payload = json_decode(json_encode($decoded), true);

        // TODO: Verify the nonce for the authentication
        // TODO: Verify that the iss field contains https://appleid.apple.com
        // TODO: Verify that the aud field is the developer’s client_id
        // TODO: Verify that the time is earlier than the exp value of the token
    }

    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    public function getExpiresIn(): int
    {
        return $this->expiresIn;
    }

    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * Unique identifier for the user from Apple.
     */
    public function getUserId(): string
    {
        return $this->payload['sub'];
    }

    /**
     * A string or Boolean value that indicates whether the service verifies the email.
     * The value can either be a string ("true" or "false") or a Boolean (true or false).
     */
    public function getEmailVerified(): mixed
    {
        return $this->payload['email_verified'];
    }

    /**
     * The email address is either the user’s real email address or the proxy address created from apple when the user
     * declined to share their real email address. Will only be provided for the initial authorization.
     */
    public function getEmail(): ?string
    {
        return $this->payload['email'] ?? null;
    }

    public function toArray(): array
    {
        return [
            'access_token' => $this->accessToken,
            'expires_in' => $this->expiresIn,
            'refresh_token' => $this->refreshToken,
            'id_token' => $this->payload,
        ];
    }
}
