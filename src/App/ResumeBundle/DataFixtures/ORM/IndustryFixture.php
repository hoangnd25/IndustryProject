<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\Industry;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class IndustryFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $industry = new Industry("Accounting");
        $manager->persist($industry);

        $industry = new Industry("Administration");
        $manager->persist($industry);

        $industry = new Industry("Advertising, Arts & Media");
        $manager->persist($industry);

        $industry = new Industry("Banking & Financial Services");
        $manager->persist($industry);

        $industry = new Industry("Call Centre & Customer Service");
        $manager->persist($industry);

        $industry = new Industry("CEO & General Management");
        $manager->persist($industry);

        $industry = new Industry("Community Services & Development");
        $manager->persist($industry);

        $industry = new Industry("Construction");
        $manager->persist($industry);

        $industry = new Industry("Consulting & Strategy");
        $manager->persist($industry);

        $industry = new Industry("Design & Architecture");
        $manager->persist($industry);

        $industry = new Industry("Education & Training");
        $manager->persist($industry);

        $industry = new Industry("Engineering");
        $manager->persist($industry);

        $industry = new Industry("Farming, Animals & Conservation");
        $manager->persist($industry);

        $industry = new Industry("Government & Defence");
        $manager->persist($industry);

        $industry = new Industry("Healthcare & Medical");
        $manager->persist($industry);

        $industry = new Industry("Hospitality & Tourism");
        $manager->persist($industry);

        $industry = new Industry("Human Resources & Recruitment");
        $manager->persist($industry);

        $industry = new Industry("Information & Communication Technology");
        $manager->persist($industry);

        $industry = new Industry("Insurance & Superannuation");
        $manager->persist($industry);

        $industry = new Industry("Legal");
        $manager->persist($industry);

        $industry = new Industry("Manufacturing, Transport & Logistics");
        $manager->persist($industry);

        $industry = new Industry("Marketing & Communications");
        $manager->persist($industry);

        $industry = new Industry("Mining, Resources & Energy");
        $manager->persist($industry);

        $industry = new Industry("Real Estate & Property");
        $manager->persist($industry);

        $industry = new Industry("Retail & Consumer Products");
        $manager->persist($industry);

        $industry = new Industry("Sales");
        $manager->persist($industry);

        $industry = new Industry("Science & Technology");
        $manager->persist($industry);

        $industry = new Industry("Self Employment");
        $manager->persist($industry);

        $industry = new Industry("Sport & Recreation");
        $manager->persist($industry);

        $industry = new Industry("Trades & Services");
        $manager->persist($industry);

        $manager->flush();
    }
}