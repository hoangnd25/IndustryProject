<?php

namespace App\ResumeBundle\Manager;

use App\ResumeBundle\Entity\Shortlist;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query;

class ShortlistManager
{

    /** @var $em EntityManager */
    protected $em;

    /**
     * ShortlistManager constructor.
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->em = $doctrine->getManager();
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
    public function getShortlist($user, $hydrate = Query::HYDRATE_ARRAY){
        $idArray = $this->getShortlistIds($user);

        if($idArray){
            $qb = $this->em->createQueryBuilder();
            $qb->select('p,u')
                ->from('AppResumeBundle:StudentProfile','p')
                ->leftJoin('p.user', 'u')
                ->where($qb->expr()->in('u.id', $idArray))
            ;
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

}