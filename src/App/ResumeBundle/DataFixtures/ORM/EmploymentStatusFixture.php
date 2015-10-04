<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\EmploymentStatus;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class EmploymentStatusFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $status = new EmploymentStatus("Employed full-time");
        $manager->persist($status);

        $status = new EmploymentStatus("Employed part-time");
        $manager->persist($status);

        $status = new EmploymentStatus("Unemployed");
        $manager->persist($status);

        $status = new EmploymentStatus("Student");
        $manager->persist($status);


        $manager->flush();
    }
}