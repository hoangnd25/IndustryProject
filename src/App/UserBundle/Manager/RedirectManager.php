<?php

namespace App\UserBundle\Manager;

use App\UserBundle\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Router;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class RedirectManager
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

    public function getRedirectResponse(User $user){


        if ($user->isStudent()) {

            $response =  new RedirectResponse($this->router->generate('student_profile_edit'));

        } elseif ($this->authChecker->isGranted(User::ROLE_ADMIN)) {

            $response =  new RedirectResponse($this->router->generate('sonata_admin_dashboard'));

        } elseif ($this->authChecker->isGranted(User::ROLE_GS1_MEMBER)) {

            $response =  new RedirectResponse($this->router->generate('member_homepage'));

        }else{

            $response = new RedirectResponse($this->router->generate('fos_user_security_logout'));

        }

        return $response;
    }
}