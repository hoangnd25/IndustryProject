<?php

namespace App\ResumeBundle\Controller;

use App\ResumeBundle\Entity\StudentCertification;
use App\ResumeBundle\Entity\StudentGS1Certification;
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

        $originalEducations = new ArrayCollection();
        // Create an ArrayCollection of the current education records in the database
        foreach ($user->getStudentProfile()->getEducations() as $edu) {
            $originalEducations->add($edu);
        }

        $originalGs1Certs = new ArrayCollection();
        // Create an ArrayCollection of the current gs1 certs in the database
        foreach ($user->getStudentProfile()->getGs1Certifications() as $cert) {
            $originalGs1Certs->add($cert);
        }

        $originalCerts = new ArrayCollection();
        // Create an ArrayCollection of the current certs in the database
        foreach ($user->getStudentProfile()->getCertifications() as $cert) {
            $originalCerts->add($cert);
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

            $avatar = $profile->getAvatar();
            if($avatar->getFile() == null && $avatar->getId() == null){
                $profile->setAvatar(null);
            }

            /*
             * Handle Education
             * ------------------------------
             */
            // remove educations deleted by the user
            foreach ($originalEducations as $edu) {
                if (false === $profile->getEducations()->contains($edu)) {
                    $em->remove($edu);
                }
            }
            // save the rest
            foreach($profile->getEducations() as $edu){
                $edu->setStudentProfile($profile);
            }

            /*
             * Handle Gs1 Cert
             * ------------------------------
             */
            // remove certs deleted by the user
            foreach ($originalGs1Certs as $cert) {
                if (false === $profile->getGs1Certifications()->contains($cert)) {
                    $em->remove($cert);
                }
            }
            // save the rest
            $hasGs1Cert = false;
            /** @var StudentGS1Certification $cert */
            foreach($profile->getGs1Certifications() as $cert){
                if($cert->getFile() == null){
                    $profile->removeGs1Certifications($cert);
                }else{
                    $cert->setStudent($profile);
                    $hasGs1Cert = true;
                }
            }
            $profile->setHasGs1Certification($hasGs1Cert);

            /*
             * Handle Certification
             * ------------------------------
             */
            // remove certs deleted by the user
            foreach ($originalCerts as $cert) {
                if (false === $profile->getCertifications()->contains($cert)) {
                    $em->remove($cert);
                }
            }
            // save the rest
            /** @var StudentCertification $cert */
            foreach($profile->getCertifications() as $cert){
                if($cert->getFile() == null){
                    $profile->removeCertifications($cert);
                }else{
                    $cert->setStudent($profile);
                }
            }

            /*
             * Handle Social Network
             * ------------------------------
             */
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
        $qb->select('partial p.{id, headline}, partial u.{id, firstName, lastName}, partial a.{id, fileName}')
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
