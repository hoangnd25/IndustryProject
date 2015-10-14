<?php

namespace App\UserBundle\Handler;

use App\UserBundle\Entity\User;
use App\UserBundle\Manager\RedirectManager;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;

class AuthenticationHandler implements AuthenticationSuccessHandlerInterface
{
    private $redirectManager;

    /**
     * AuthenticationHandler constructor.
     * @param $redirectManager
     */
    public function __construct(RedirectManager $redirectManager)
    {
        $this->redirectManager = $redirectManager;
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

        return $this->redirectManager->getRedirectResponse($user);
    }


}