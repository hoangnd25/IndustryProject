<?php

namespace App\ResumeBundle\Controller;

use App\ResumeBundle\Entity\StudentProfile;
use App\UserBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Elastica\Query;
use Elastica\QueryBuilder;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class StudentProfileController extends Controller
{
    /**
     * @Route("/my-profile/edit", name="student_profile_edit")
     * @Security("has_role('ROLE_STUDENT')")
     * @Template()
     */
    public function editAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        if($user->getStudentProfile() == null)
            $user->setStudentProfile(new StudentProfile());

        if($user->getStudentProfile()->getContactEmail() == null)
            $user->getStudentProfile()->setContactEmail($user->getEmail());

        $form = $this->createForm('student_profile', $user->getStudentProfile(), array('label'=>$user->getUsername()));

        $originalSocialLinks = new ArrayCollection();
        // Create an ArrayCollection of the current social links in the database
        foreach ($user->getStudentProfile()->getSocialNetworks() as $link) {
            $originalSocialLinks->add($link);
        }

        $form->handleRequest($request);

        if($request->getMethod() == 'POST' && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $profile = $user->getStudentProfile();
            $profile->setUser($user);

            $resume = $profile->getResume();
            if($resume->getFile() == null && $resume->getId() == null){
                $profile->setResume(null);
            }

            // remove links deleted by the user
            foreach ($originalSocialLinks as $link) {
                if (false === $profile->getSocialNetworks()->contains($link)) {
                    $em->remove($link);
                }
            }
            // save the rest
            foreach($profile->getSocialNetworks() as $social){
                $social->setStudent($profile);
            }

            $em->persist($profile);
            $em->flush();

            return $this->redirectToRoute('student_profile_edit', array('id'=>$user->getId()));
        }

        /** @var User $user */
        return array(
            'form'=> $form->createView(),
            'user' => $user
        );
    }

    /**
     * @Route("/student-profile/{id}", name="student_profile_show")
     * @Template()
     * @ParamConverter("user", class="AppUserBundle:User")
     */
    public function showAction($user)
    {
        /** @var User $user */
        if(!$user->isStudent())
            throw new NotFoundHttpException("Student is invalid");

        if($user->getStudentProfile() == null)
            throw new NotFoundHttpException("Student profile is invalid");

        return array('user'=>$user);
    }

    /**
     * @Route("/students", name="student_profile_list")
     * @Template()
     * @Security("has_role('ROLE_GS1_MEMBER')")
     */
    public function listAction(Request $request){
        $keyword = $request->get('keyword');

        $idArray = array();
        if($keyword != null){
            $query = new Query();
            $query->setSize(1000);

            $fuzzyQuery = new Query\FuzzyLikeThis();
            $fuzzyQuery->setLikeText($keyword);
            $fuzzyQuery->setMinSimilarity(0.7);

            $query->setQuery($fuzzyQuery);

            $results = $this->get('fos_elastica.index.app.student_profile')->search($query);

            foreach($results as $item){
                $idArray[] = $item->getId();
            }
        }

        /** @var EntityManager $em */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();
        $qb->select('partial p.{id, firstName, lastName, headline}, partial u.{id}, partial a.{id, fileName}')
            ->from('AppResumeBundle:StudentProfile','p')
            ->leftJoin('p.user','u')
            ->leftJoin('p.avatar','a')
            ->where($qb->expr()->eq('u.studentProfileVisibility', User::VISIBILITY_VISIBLE))
            ->orderBy('u.id', 'desc')
        ;

        if($keyword != null){
            if(!empty($idArray)){
                $qb->andWhere($qb->expr()->in('p.id', $idArray));
            }else{
                $qb->andWhere($qb->expr()->eq('u.id', -1));
            }
        }

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return array(
            'profiles' => $pagination,
            'filter' => array(
                'keyword' => $keyword
            ),
            'total' => array(
                'shortlist' => null
            )
        );
    }
}
