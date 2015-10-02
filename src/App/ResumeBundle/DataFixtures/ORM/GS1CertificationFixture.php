<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\GS1Certification;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class GS1CertificationFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $cert = new GS1Certification("GS1 Academic Certification");
        $manager->persist($cert);

        $cert = new GS1Certification("GS1 Academic Certification with Distinction");
        $manager->persist($cert);

        $manager->flush();
    }
}