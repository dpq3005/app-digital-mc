<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class ApiKeyNricUser implements UserInterface
{
    const ROLE_USER = 'ROLE_NRIC_USER';

    public $apiKey;
    public $beneficiaryNric;
    public $organisationUuid;

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return [self::ROLE_USER];
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->apiKey;
    }

    /**
     * @inheritDoc
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->beneficiaryNric;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        return null;
    }
}