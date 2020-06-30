<?php

namespace App\Security;

use App\Entity\Dmc\MedicalChit;
use Doctrine\Persistence\ManagerRegistry;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class ApiKeyNricAuthenticator extends AbstractGuardAuthenticator
{
    private $registry, $container, $jwtManager;

    public function __construct(ManagerRegistry $registry, ContainerInterface $container, JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->registry = $registry;
        $this->container = $container;
        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
    }

    public function supports(Request $request)
    {
        if (!$request->isMethod('POST')) {
            return false;
        }
        return
            !empty($request->request->get('api_key'))
            && !empty($request->request->get('nric'));
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'nric' => $request->request->get('nric'),
            'apiKey' => $request->request->get('api_key'),
        ];
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $usernameNric = $credentials['nric'];
        if (empty($passwordApiKey = $credentials['apiKey'])) {
            throw new CustomUserMessageAuthenticationException('Login not valid using empty password.');
        }

        $dmcRepo = $this->registry->getRepository(MedicalChit::class);

        /** @var MedicalChit $dmc */
        $dmc = $dmcRepo->findOneBy(['beneficiaryNric' => $usernameNric,
            'redeemed' => false,
        ]);

        if (empty($dmc)) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Login not valid.');
        }

        $user = new ApiKeyNricUser();
        $user->apiKey = $passwordApiKey;
        $user->beneficiaryNric = $usernameNric;
        $user->organisationUuid = $dmc->getBenefitProvider()->getOrganisation()->getUuid();
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return !empty($user) && $credentials['apiKey'] === $this->container->getParameter('api_key');
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new CustomUserMessageAuthenticationException('Invalid credentials !');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var ApiKeyNricUser $user */
        $user = $token->getUser();
        $jwt = $this->jwtManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $event = new AuthenticationSuccessEvent(['token' => $jwt, 'orgUuid' => $user->organisationUuid], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $response->setData($data);

        return $response;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required... !!!!! '.$authException->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
