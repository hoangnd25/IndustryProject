<?php

namespace App\ResumeBundle\Manager;

use App\ResumeBundle\Entity\Shortlist;
use App\ResumeBundle\Entity\StatShortlist;
use App\ResumeBundle\Model\StudentFilter;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class ShortlistManager
{

    /** @var $em EntityManager */
    protected $em;
    protected $searchManager;

    /**
     * ShortlistManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine, SearchManager $searchManager)
    {
        $this->em = $doctrine->getManager();
        $this->searchManager = $searchManager;
    }

    /**
     * @param $user
     * @return array
     */
    public function getShortlistIds($user){
        $qb = $this->em->createQueryBuilder();
        $qb->select('student.id')
            ->from('AppResumeBundle:Shortlist','s')
            ->leftJoin('s.student', 'student')
            ->where($qb->expr()->eq('s.user', $user->getId()))
        ;
        $idArray = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        $idArray = array_map(function($item){
            return $item['id'];
        }, $idArray);

        return $idArray;
    }

    /**
     * @param $user
     * @param int $hydrate
     * @return array
     */
    public function getShortlist($user, $hydrate = Query::HYDRATE_ARRAY, StudentFilter $filter = null){
        $idArray = $this->getShortlistIds($user);

        if($idArray){
            $qb = $this->em->createQueryBuilder();
            $qb->select('partial p.{id, headline, contactEmail, contactNumber}, partial u.{id, firstName, lastName}, partial a.{id, fileName}')
                ->from('AppResumeBundle:StudentProfile','p')
                ->leftJoin('p.user','u')
                ->leftJoin('p.avatar','a')
                ->leftJoin('p.industryPreference','ip')
                ->leftJoin('p.gs1Certifications','gs1')
                ->leftJoin('p.employmentStatus','es')
                ->leftJoin('p.educations','edu')
                ->where($qb->expr()->in('u.id', $idArray))
            ;

            if($filter !== null){

                $keywordFilteredIdArray = array();
                if($filter->getKeyword()!= null){
                    $results = $this->searchManager->search($filter->getKeyword());
                    foreach($results as $item){
                        $keywordFilteredIdArray[] = $item->getId();
                    }
                }

                if(null !== $industryIds = $this->getIdArray($filter->getIndustry())){
                    $qb->andWhere($qb->expr()->in('ip', $industryIds));
                }

                if(null !== $gs1CertArray = $this->getIdArray($filter->getGs1Certification())){
                    $qb->andWhere($qb->expr()->in('gs1.type', $gs1CertArray));
                }

                if(null !== $employmentStatusArray = $this->getIdArray($filter->getEmploymentStatus())){
                    $qb->andWhere($qb->expr()->in('es', $employmentStatusArray));
                }

                if(null !== $institutionArray = $this->getIdArray($filter->getInstitution())){
                    $qb->andWhere($qb->expr()->in('edu.institution', $institutionArray));
                }

                if($filter->getCountry() !== null && count($filter->getCountry()) > 0){
                    $qb->andWhere($qb->expr()->in('p.country', $filter->getCountry()));
                }

                if(null !== ($workingRight = $filter->hasWorkingRight())){
                    $qb->andWhere($qb->expr()->eq('p.workingRight', $workingRight ? "1" : "0"));
                }

                if($filter->getKeyword() != null){
                    if(!empty($keywordFilteredIdArray)){
                        $qb->andWhere($qb->expr()->in('p.id', $keywordFilteredIdArray));
                    }else{
                        $qb->andWhere($qb->expr()->eq('u.id', -1));
                    }
                }
            }

            $results = $qb->getQuery()->getResult($hydrate);
        }else{
            $results = array();
        }

        return $results;
    }

    /**
     * @param $user
     * @param $student
     * @return bool
     */
    public function add($user, $student){
        $qb = $this->em->createQueryBuilder();
        $qb->select('s')
            ->from('AppResumeBundle:Shortlist','s')
            ->where($qb->expr()->eq('s.user', $user->getId()))
            ->andwhere($qb->expr()->eq('s.student', $student->getId()))
        ;

        $result = $qb->getQuery()->setMaxResults(1)->getResult();

        if(!$result){
            $shortlist = new Shortlist();
            $shortlist->setUser($user);
            $shortlist->setStudent($student);

            $stat = new StatShortlist();
            $stat->setStudent($student->getStudentProfile());
            $this->em->persist($stat);

            $this->em->persist($shortlist);
            $this->em->flush();

            return true;
        }else{
            return false;
        }
    }

    public function remove($user, $student){
        $qb = $this->em->createQueryBuilder();
        $qb->delete('AppResumeBundle:Shortlist','s')
            ->where($qb->expr()->eq('s.user', $user->getId()))
            ->andwhere($qb->expr()->eq('s.student', $student->getId()))
        ;

        $result = $qb->getQuery()->execute();
    }

    public function clear($user){
        $qb = $this->em->createQueryBuilder();
        $qb->delete('AppResumeBundle:Shortlist','s')
            ->where($qb->expr()->eq('s.user', $user->getId()))
        ;

        $result = $qb->getQuery()->execute();
    }

    protected function getIdArray($collection){
        if(!$collection instanceof ArrayCollection)
            return null;
        if(count($collection) < 1)
            return null;

        return array_map(function($item){
            return $item->getId();
        }, $collection->toArray());
    }
}