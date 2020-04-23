<?php

namespace App\Security;

use App\Entity\Organisation\Organisation;
use App\Entity\Security\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Response\JWTAuthenticationSuccessResponse;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Events;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface as ContractsEventDispatcherInterface;

class CompanyCodeUsernamePasswordAuthenticator extends AbstractGuardAuthenticator
{
    private $entityManager;
    private $router;
    private $csrfTokenManager;
    private $passwordEncoder;
    protected $jwtManager;
    protected $dispatcher;

    public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder, JWTTokenManagerInterface $jwtManager, EventDispatcherInterface $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;

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
                array_key_exists('org-code', $data) && !empty($data['org-code'])
                &&
                array_key_exists('username', $data) && !empty($data['username'])
                &&
                array_key_exists('password', $data) && !empty($data['password']);
        }
        return
            !empty($request->request->get('org-code'))
            && !empty($request->request->get('username'))
            && !empty($request->request->get('password'));
    }

    public function getCredentials(Request $request)
    {
        if ($request->getContentType() === 'json') {
            $dataJson = $request->getContent();
            $data = json_decode($dataJson, true);
            $orgCode = $data['org-code'];
            $credentials = [
                'org-code' => $orgCode,
                'username' => $data['username'],
                'password' => $data['password'],
            ];
        } else {
            $orgCode = $request->request->get('org-code');
            $credentials = [
                'org-code' => $orgCode,
                'username' => $request->request->get('username'),
                'password' => $request->request->get('password'),
            ];
        }

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $orgCode = $credentials['org-code'];
        if (!empty($orgCode)) {
            /** @var Organisation $org */
            $org = $this->entityManager->getRepository(Organisation::class)->findOneBy(['supervisorCode' => strtoupper($orgCode)]);

            $user = $org->findOneUserByUsername($credentials['username']);
        }

        if (empty($user)) {
            // fail authentication with a custom error
//            throw new \Exception('aaaaaaaaaaaaaaaaaaaa');
            throw new CustomUserMessageAuthenticationException('Login not valid.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        /** @var User $user */
        $user = $token->getUser();
        $jwt = $this->jwtManager->create($user);

        $response = new JWTAuthenticationSuccessResponse($jwt);
        if ($org = $user->getOrganisation()) {
            $benefitProviderUuid = $org->getBenefitProvider()->getUuid();
        } else {
            $benefitProviderUuid = null;
        }
        $event = new AuthenticationSuccessEvent(['token' => $jwt, 'benefitProviderUuid' => $benefitProviderUuid, 'orgUuid' => $org->getUuid()], $user, $response);

        $this->dispatcher->dispatch($event, Events::AUTHENTICATION_SUCCESS);

        $data = $event->getData();

        $response->setData($data);

        return $response;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
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
