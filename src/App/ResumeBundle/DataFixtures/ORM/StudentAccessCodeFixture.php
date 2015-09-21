<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\StudentAccessCode;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Provider\Base;

class StudentAccessCodeFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Generate 20 access codes
        $faker = new Generator();
        $faker->addProvider(new Base($faker));

        for($i=0; $i<20; $i++){
            $code = new StudentAccessCode();
            $code->setCode($faker->randomNumber(8));
            $manager->persist($code);
        }

        $manager->flush();
    }
}