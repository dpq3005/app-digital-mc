<?php

namespace App\DoctrineListener\Security;

use App\Entity\Security\User;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserPasswordEncoder
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function encodePassword(User $user, LifecycleEventArgs $event)
    {
        if (empty($plainPassword = $user->getPlainPassword())) {
            return;
        }
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            $plainPassword
        ));
    }
}