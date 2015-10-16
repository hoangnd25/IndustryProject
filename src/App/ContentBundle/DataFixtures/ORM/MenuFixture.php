<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ContentBundle\Entity\Content;
use App\ContentBundle\Entity\Menu;
use App\ContentBundle\Entity\MenuItem;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class MenuFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $menu = new Menu('main', 'Main menu');
        $menu->addItems(new MenuItem('Membership','http://www.gs1au.org/membership/'));
        $menu->addItems(new MenuItem('Standards','http://www.gs1au.org/products/index.asp'));
        $menu->addItems(new MenuItem('Services','http://www.gs1au.org/services/'));
        $menu->addItems(new MenuItem('Industry','http://www.gs1au.org/industry/'));
        $menu->addItems(new MenuItem('Join GS1','http://www.gs1express.com.au/barcodes/'));
        $manager->persist($menu);

        $menu = new Menu('secondary', 'Secondary menu');
        $menu->addItems(new MenuItem('GS1 Australia Home','http://www.gs1au.org/'));
        $menu->addItems(new MenuItem('Get a barcode','http://www.gs1express.com.au/barcodes/'));
        $manager->persist($menu);

        $menu = new Menu('footer-1', 'About GS1 Australia');
        $menu->addItems(new MenuItem('Learn about GS1','http://www.gs1au.org/'));
        $manager->persist($menu);

        $menu = new Menu('footer-2', 'Events & Trainings');
        $menu->addItems(new MenuItem('GS1 Events and Trainings','http://www.gs1au.org/events/'));
        $manager->persist($menu);

        $menu = new Menu('footer-3', 'Contact');
        $menu->addItems(new MenuItem('Contact Us','http://www.gs1au.org/contact_us/'));
        $manager->persist($menu);

        $menu = new Menu('footer-4', 'You are in GS1 Australia');
        $menu->addItems(new MenuItem('Terms of Trade','http://www.gs1au.org/terms-of-trade.asp'));
        $menu->addItems(new MenuItem('Privacy Policy','http://www.gs1au.org/privacy.asp'));
        $manager->persist($menu);

        $menu = new Menu('footer-5', 'Get in touch');
        $menu->addItems(new MenuItem('0423 884 455','tel:+61 423 884 455', 'footer-contact'));
        $menu->addItems(new MenuItem('','mailto:customer.service@gs1au.org', 'icon icon-envelope color-white email'));
        $menu->addItems(new MenuItem('','http://www.youtube.com/user/GS1Australia', 'icon icon-youtube color-navy'));
        $manager->persist($menu);

        $manager->flush();
    }
}