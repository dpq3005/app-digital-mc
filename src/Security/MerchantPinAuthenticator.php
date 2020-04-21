<?php

namespace App\Security;

use App\Entity\Organisation\Organisation;
use App\Entity\Security\User;
use App\Service\HttpService;
use Doctrine\ORM\EntityManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class MerchantPinAuthenticator extends AbstractGuardAuthenticator
{
    private $entityManager, $http;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    protected $jwtManager;
    protected $dispatcher;

    public function __construct(HttpService $http, EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
        $this->http = $http;

        $this->jwtManager = $jwtManager;
        $this->dispatcher = $dispatcher;
    }

    public function supports(Request $request)
    {
//        return 'app_login' === $request->attributes->get('_route')
//            && $request->isMethod('POST');
        if (!$request->isMethod('POST')) {
            return false;
        }
        if ($request->getContentType() === 'json') {
            $content = $request->getContent();
            $data = json_decode($content, true);
            return
                array_key_exists('uuid', $data) && !empty($data['uuid'])
                &&
                array_key_exists('pin', $data) && !empty($data['pin']);
        }

        return
            !empty($request->request->get('uuid'))
            && !empty($request->request->get('pin'));
    }

    public function getCredentials(Request $request)
    {
        if ($request->getContentType() === 'json') {
            $dataJson = $request->getContent();
            $data = json_decode($dataJson, true);
            $credentials = [
                'uuid' => $data['uuid'],
                'pin' => $data['pin'],
            ];
        } else {
            $credentials = [
                'uuid' => $request->request->get('uuid'),
                'pin' => $request->request->getInt('pin'),
            ];
        }
        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $uuid = $credentials['uuid'];
        if (empty($credentials['pin'])) {
            throw new CustomUserMessageAuthenticationException('Login not valid using empty password.');
        }

        $response = $this->http->get('merchants', $uuid);
        $data = $response['body'];
        if (empty($data)) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Login not valid.');
        }
        $user = new MerchantPinUser();
        $user->pin = (int) $data->pin;
        $user->uuid = $uuid;
        $user->organisationUuid = $data->organisationUuid;

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if ($user instanceof MerchantPinUser) {
            return $credentials['pin'] === $user->pin;
        }
        return false;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var MerchantPinUser $user */
        $user = $token->getUser();
        $jwt = $this->jwtManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);
        $event = new AuthenticationSuccessEvent(['token' => $jwt, 'orgUuid' => $user->organisationUuid], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $response->setData($data);

        return $response;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('merchant_pin_token');
    }

    /**
     * Returns a response that directs the user to authenticate.
     *
     * This is called when an anonymous request accesses a resource that
     * requires authentication. The job of this method is to return some
     * response that "helps" the user start into the authentication process.
     *
     * Examples:
     *
     * - For a form login, you might redirect to the login page
     *
     *     return new RedirectResponse('/login');
     *
     * - For an API token authentication system, you return a 401 response
     *
     *     return new Response('Auth header required', 401);
     *
     * @param Request $request The request that resulted in an AuthenticationException
     * @param AuthenticationException $authException The exception that started the authentication process
     *
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            // you might translate this message
            'message' => 'Authentication Required... !!!'.$authException->getMessage(),
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Called when authentication executed, but failed (e.g. wrong username password).
     *
     * This should return the Response sent back to the user, like a
     * RedirectResponse to the login page or a 403 response.
     *
     * If you return null, the request will continue, but the user will
     * not be authenticated. This is probably not what you want to do.
     *
     * @param Request $request
     * @param AuthenticationException $exception
     *
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        throw new CustomUserMessageAuthenticationException('Invalid credentials !');
    }

    /**
     * Does this method support remember me cookies?
     *
     * Remember me cookie will be set if *all* of the following are met:
     *  A) This method returns true
     *  B) The remember_me key under your firewall is configured
     *  C) The "remember me" functionality is activated. This is usually
     *      done by having a _remember_me checkbox in your form, but
     *      can be configured by the "always_remember_me" and "remember_me_parameter"
     *      parameters under the "remember_me" firewall key
     *  D) The onAuthenticationSuccess method returns a Response object
     *
     * @return bool
     */
    public function supportsRememberMe()
    {
        return false;
    }
}