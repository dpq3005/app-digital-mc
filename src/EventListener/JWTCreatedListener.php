<?php

namespace App\EventListener;

use App\Entity\Security\User;
use App\Security\MerchantPinUser;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTCreatedListener
{
    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @param JWTCreatedEvent $event
     */
    public function onJWTCreated(JWTCreatedEvent $event)
    {
        $request = $this->requestStack->getCurrentRequest();
        $user = $event->getUser();
        $payload = $event->getData();
        if (!empty($request)) {
            $payload['ip'] = $request->getClientIp();
        } else {
            $payload['ip'] = null;
        }

        if ($user instanceof UserInterface) {
            if ($user instanceof User) {
                $payload['organisationUuid'] = $user->getOrganisation()->getUuid();
            } elseif ($user instanceof MerchantPinUser) {
                $payload['organisationUuid'] = $user->organisationUuid;
            }
        }

        $event->setData($payload);
//        $header = $event->getHeader();
//        $header['cty'] = 'JWT';
//
//        $event->setHeader($header);
    }
}
