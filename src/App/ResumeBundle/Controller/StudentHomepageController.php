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

class StudentHomepageController extends Controller
{
    /**
     * @Route("/student", name="student_homepage")
     * @Security("has_role('ROLE_STUDENT')")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $lastMonth = array();
        for($i = 29; $i > 0; $i--) {
            $lastMonth[] = (new \DateTime('now', new \DateTimeZone('UTC')))->modify('-' . $i . ' days');
        }
        $lastMonth[] =  (new \DateTime('now', new \DateTimeZone('UTC')));

        $studentId = $this->getUser()->getStudentProfile()->getId();
        $dql = 'SELECT
                    DATE(s.created) AS dateGroup,
                    CAST(s.created as date) AS HIDDEN groupDateGrp ,
                    COUNT(s.id) as num
                FROM AppResumeBundle:StatProfileView s
                WHERE s.student = :id AND s.created >= DATE(:lastDate)
                GROUP BY groupDateGrp
        ';
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        $profileViews = $query
            ->setParameter('id', $studentId)
            ->setParameter('lastDate', $lastMonth[0])
            ->getResult()
        ;

        $dql = 'SELECT
                    DATE(s.created) AS dateGroup,
                    CAST(s.created as date) AS HIDDEN groupDateGrp ,
                    COUNT(s.id) as num
                FROM AppResumeBundle:StatShortlist s
                WHERE s.student = :id AND s.created >= :lastDate
                GROUP BY groupDateGrp
        ';
        $query = $this->getDoctrine()->getManager()->createQuery($dql);
        $shortlists = $query
            ->setParameter('id', $studentId)
            ->setParameter('lastDate', $lastMonth[0])
            ->getResult()
        ;

        $result = array();
        $i=29;
        foreach($lastMonth as $date){
            $result['month']['profileView'][$i] = 0;
            foreach($profileViews as $stat){
                if($stat['dateGroup'] == $date->format('Y-m-d')){
                    $result['month']['profileView'][$i] = $stat['num'];
                    break;
                }
            }

            $result['month']['shortlist'][$i] = 0;
            foreach($shortlists as $stat){
                if($stat['dateGroup'] == $date->format('Y-m-d')){
                    $result['month']['shortlist'][$i] = $stat['num'];
                    break;
                }
            }

            $date->setTimezone(new \DateTimeZone(date_default_timezone_get()));
            if($i%3==0){
                $result['monthLabel'][] = $date->format('d/m');
            }else{
                $result['monthLabel'][] = '';
            }
            $i--;
        }
        return $result;
    }
}
