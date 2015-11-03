<?php

namespace App\ResumeBundle\Controller;

use App\ResumeBundle\Entity\MemberProfile;
use App\ResumeBundle\Form\Type\MemberProfileType;
use App\ResumeBundle\Model\StudentFilter;
use App\UserBundle\Entity\User;
use Doctrine\ORM\Query;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Constraints\Valid;

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

    /**
     * @Route("/member/edit-profile", name="member_profile_edit")
     * @Security("has_role('ROLE_GS1_MEMBER')")
     * @Template()
     */
    public function editAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home", $this->get("router")->generate("member_homepage"));
        $breadcrumbs->addItem("Edit profile");

        /** @var User $user */
        $user = $this->getUser();
        $profile = $user->getMemberProfile();
        if($profile === null){
            $profile = new MemberProfile();
            $profile->setUser($user);
        }

        $form = $this->createForm(new MemberProfileType(), $profile, array(
            'label'=>$user->getUsername(),
            'show_name' => true,
            'attr' => array(
                'novalidate' => 'novalidate'
            ),
            'constraints' => array(new Valid())
        ));

        $form->handleRequest($request);

        if($request->getMethod() == 'POST' && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profile);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Profile was updated successfully.');
            return $this->redirectToRoute('member_profile_edit', array('id'=>$user->getId()));
        }elseif($request->getMethod() == 'POST' && !$form->isValid()){
            $this->get('session')->getFlashBag()->add('warning', 'Please check your form.');
        }

        return array(
            'form' => $form->createView(),
            'user' => $user
        );
    }
}
