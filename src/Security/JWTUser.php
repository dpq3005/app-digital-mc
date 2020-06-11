<?php

namespace App\Security;

use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;

final class JWTUser extends \Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser implements JWTUserInterface
{
    const ROLE_SUPERVISOR = 'ROLE_SUPERVISOR';
    const ROLE_MERCHANT_USER = MerchantPinUser::ROLE_USER;

    private $organisationUuid, $ip;
    public function __construct($username, array $roles, $organisationUuid, $ip)
    {
        $this->organisationUuid = $organisationUuid;
        $this->ip = $ip;
        parent::__construct($username, $roles);
    }

    public static function createFromPayload($username, array $payload)
    {
        return new self(
            $username,
            $payload['roles'], // Added by default
            $payload['organisationUuid'],
            $payload['ip']
        );
    }

    public function hasRole($role)
    {
        $roles = $this->getRoles();
        foreach ($roles as $r) {
            if ($r === $role) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return mixed
     */
    public function getOrganisationUuid()
    {
        return $this->organisationUuid;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }
}
