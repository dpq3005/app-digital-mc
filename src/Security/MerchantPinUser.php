<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserInterface;

class MerchantPinUser implements UserInterface
{
    const ROLE_USER = 'ROLE_MERCHANT_USER';

    public $uuid;
    public $pin;
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
        return $this->pin;
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
        return $this->uuid;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
        return null;
    }
}