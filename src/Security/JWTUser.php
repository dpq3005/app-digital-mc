<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class JWTUser extends \Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser implements JWTUserInterface
{
    public function __construct($username, array $roles)
    {
        parent::__construct($username, $roles);
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['roles'] // Added by default
        );
    }
}
