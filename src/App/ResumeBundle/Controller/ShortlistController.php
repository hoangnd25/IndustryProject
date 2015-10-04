<?php

namespace App\ResumeBundle\Controller;

use App\ResumeBundle\Model\StudentFilter;
use App\UserBundle\Entity\User;
use Doctrine\ORM\Query;
use libphonenumber\PhoneNumberFormat;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ShortlistController extends Controller
{

    /**
     * @Route("/shortlist", name="shortlist")
     * @Security("has_role('ROLE_GS1_MEMBER')")
     * @Template()
     */
    public function showAction(Request $request)
    {
        $filterForm = $this->createForm('filter', new StudentFilter());
        $filterForm->handleRequest($request);
        /** @var StudentFilter $filterData */
        $filter = $filterForm->getData();

        /** @var User $user */
        $user = $this->getUser();

        $results = $this->get('manager.shortlist')->getShortlist($user, Query::HYDRATE_ARRAY);

        return array(
            'shortlist' => $results,
            'filter' => $filterForm->createView()
        );
    }

    /**
     * @Route("/shortlist/add/{studentId}", name="shortlist_add")
     * @ParamConverter("student", class="AppUserBundle:User", options={"id" = "studentId"})
     * @Security("has_role('ROLE_GS1_MEMBER')")
     */
    public function addAction(Request $request, User $student)
    {
        /** @var User $user */
        $user = $this->getUser();

        $result = $this->get('manager.shortlist')->add($user, $student);

        if($result){
            $this->get('session')->getFlashBag()->set("success", $student->getStudentProfile()->getFullName()." added to shortlist successfully");
        }else{
            $this->get('session')->getFlashBag()->set("error", $student->getStudentProfile()->getFullName()." is already in the shortlist");
        }

        $redirectUrl = $request->get('redirect');
        if($redirectUrl){
            $response = $this->redirect($redirectUrl);
        }else{
            $response = $this->redirectToRoute('shortlist');
        }

        return $response;
    }

    /**
     * @Route("/shortlist/remove/{studentId}", name="shortlist_remove")
     * @ParamConverter("student", class="AppUserBundle:User", options={"id" = "studentId"})
     * @Security("has_role('ROLE_GS1_MEMBER')")
     */
    public function removeAction(Request $request, User $student)
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->get('manager.shortlist')->remove($user, $student);

        $response = $this->redirectToRoute('shortlist');
        return $response;
    }

    /**
     * @Route("/shortlist/clear", name="shortlist_clear")
     * @Security("has_role('ROLE_GS1_MEMBER')")
     */
    public function clearAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $this->get('manager.shortlist')->clear($user);

        $response = $this->redirectToRoute('shortlist');
        return $response;
    }

    /**
     * @Route("/shortlist/export", name="shortlist_export")
     * @Security("has_role('ROLE_GS1_MEMBER')")
     */
    public function exportAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        $results = $this->get('manager.shortlist')->getShortlist($user, Query::HYDRATE_ARRAY);
        $phoneFormatter = $this->get('libphonenumber.phone_number_util');

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();
        $phpExcelObject->setActiveSheetIndex(0)
            ->setCellValue('A1', 'First name')
            ->setCellValue('B1', 'Last name')
            ->setCellValue('C1', 'Email')
            ->setCellValue('D1', 'Phone')
        ;
        $phpExcelObject->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

        $index = 2;
        foreach($results as $student){
            $phone = "";
            if(array_key_exists("contactNumber", $student)){
                if($student["contactNumber"])
                    $phone = $phoneFormatter->format(
                        $student['contactNumber'],
                        PhoneNumberFormat::INTERNATIONAL
                    );
            }

            $firstName = array_key_exists("firstName", $student) ? $student["firstName"] : "";
            $lastName = array_key_exists("lastName", $student) ? $student["lastName"] : "";
            $contactEmail = array_key_exists("contactEmail", $student) ? $student["contactEmail"] : "";

            $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A'.$index, $firstName)
                ->setCellValue('B'.$index, $lastName)
                ->setCellValue('C'.$index, $contactEmail)
                ->setCellValue('D'.$index, $phone)
            ;

            $index++;
        }

        $phpExcelObject->getActiveSheet()->setTitle('Students');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $dispositionHeader = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            'student.xls'
        );
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');
        $response->headers->set('Content-Disposition', $dispositionHeader);

        return $response;
    }
}
