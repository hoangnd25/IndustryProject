<?php

namespace App\ResumeBundle\Controller;

use App\ResumeBundle\Model\StudentFilter;
use App\UserBundle\Entity\User;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MemberHomepageController extends Controller
{
    /**
     * @Route("/member", name="member_homepage")
     * @Security("has_role('ROLE_GS1_MEMBER')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $filterForm = $this->createForm('filter', new StudentFilter());
        $filterForm->handleRequest($request);
        /** @var StudentFilter $filterData */
        $filter = $filterForm->getData();

        return array(
        );
    }
}
