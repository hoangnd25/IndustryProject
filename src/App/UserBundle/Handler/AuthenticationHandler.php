<?php

namespace App\UserBundle\Handler;

use App\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    /** @var  AuthorizationChecker $authChecker */
    protected $authChecker;
    /** @var  Router $router */
    protected $router;

    /**
     * constructor.
     * @param AuthorizationChecker $authChecker
     * @param Router $router
     */
    public function __construct(AuthorizationChecker $authChecker, Router $router)
    {
        $this->authChecker = $authChecker;
        $this->router = $router;
    }

    /**
     * This is called when an interactive authentication attempt succeeds. This
     * is called by authentication listeners inheriting from
     * AbstractAuthenticationListener.
     *
     * @param Request $request
     * @param TokenInterface $token
     *
     * @return Response never null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($targetPath = $request->get('_target_path')) {
            return new RedirectResponse($targetPath);
        }

        /** @var User $user */
        $user = $token->getUser();

        if ($user->isStudent()) {

            return new RedirectResponse($this->router->generate('student_profile_edit'));

        } elseif ($this->authChecker->isGranted(User::ROLE_ADMIN)) {

            return new RedirectResponse($this->router->generate('sonata_admin_dashboard'));

        } elseif ($this->authChecker->isGranted(User::ROLE_GS1_MEMBER)) {

            return new RedirectResponse($this->router->generate('student_profile_list'));

        }
    }


}