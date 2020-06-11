<?php

namespace App\EventListener;

use App\Entity\Security\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
use Symfony\Component\HttpFoundation\RequestStack;

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

        /** @var User $user */
        $user = $event->getUser();
        $payload = $event->getData();

        if (!empty($request)) {
            $payload['ip'] = $request->getClientIp();
        } else {
            $payload['ip'] = null;
        }

        $payload['organisationUuid'] = $user->getOrganisation()->getUuid();

        $event->setData($payload);
//        $header = $event->getHeader();
//        $header['cty'] = 'JWT';
//
//        $event->setHeader($header);
    }
}
