<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\Institution;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EducationFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $institution = new Institution("Curtin University");
        $manager->persist($institution);

        $institution = new Institution("Deakin University");
        $manager->persist($institution);

        $institution = new Institution("Melbourne University");
        $manager->persist($institution);

        $institution = new Institution("Monash University");
        $manager->persist($institution);

        $institution = new Institution("RMIT");
        $manager->persist($institution);

        $institution = new Institution("Swinburne University of Technology");
        $manager->persist($institution);

        $institution = new Institution("University of Newcastle");
        $manager->persist($institution);

        $institution = new Institution("University of Sydney");
        $manager->persist($institution);

        $institution = new Institution("University of South Australia");
        $manager->persist($institution);

        $institution = new Institution("University of Technology Sydney");
        $manager->persist($institution);

        $institution = new Institution("Victoria University");
        $manager->persist($institution);

        $manager->flush();
    }
}