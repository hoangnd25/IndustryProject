<?php

namespace App\UserBundle\DataFixtures\ORM;

use App\ResumeBundle\Entity\StatProfileView;
use App\ResumeBundle\Entity\StatShortlist;
use App\ResumeBundle\Entity\StudentProfile;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use App\UserBundle\Entity\User;
use Faker\Generator;
use Faker\Provider\en_AU\Internet;
use Faker\Provider\en_US\Person;
use Faker\Provider\en_AU\PhoneNumber;
use Faker\Provider\Lorem;

class UserFixture implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $faker = new Generator();
        $faker->addProvider(new Person($faker));
        $faker->addProvider(new Internet($faker));
        $faker->addProvider(new PhoneNumber($faker));
        $faker->addProvider(new Lorem($faker));

        // Generate default admin
        $admin = new User();
        $admin->setPlainPassword('123456');
        $admin->setEmail('admin@gs1au.org');
        $admin->setEnabled(true);
        $admin->setType(User::ROLE_ADMIN);
        $admin->setFirstName($faker->firstName);
        $admin->setLastName($faker->lastName);
        $manager->persist($admin);

        // Generate default student
        $student = new User();
        $student->setPlainPassword('123456');
        $student->setEmail('student@gs1au.org');
        $student->setEnabled(true);
        $student->setType(User::ROLE_STUDENT);
        $student->setStudentProfileVisibility(User::VISIBILITY_VISIBLE);
        $student->setFirstName($faker->firstName);
        $student->setLastName($faker->lastName);

        $faker->seed(rand(1,10));
        $studentProfile = new StudentProfile();
        $studentProfile->setContactEmail($student->getEmail());
        $studentProfile->setHeadline($faker->sentence);

        $student->setStudentProfile($studentProfile);
        $manager->persist($student);

        // Generate stat for student
        for($i = 0; $i < 30; $i++){
            $numProfileView = rand(0, 15);
            $numShortlist = rand(0, round($numProfileView/2));

            for($y = 0; $y < $numProfileView; $y++){
                $time = (new \DateTime('now'))->modify('-'.$i.' days');
                $time->setTime(rand(1,11), rand(1,30), 0);
                $statProfileView = new StatProfileView();
                $statProfileView->setCreated($time);
                $statProfileView->setStudent($studentProfile);
                $manager->persist($statProfileView);
            }

            for($z = 0; $z < $numShortlist; $z++){
                $time = (new \DateTime('now'))->modify('-'.$i.' days');
                $time->setTime(rand(1,11), rand(1,30), 0);
                $statShortlist = new StatShortlist();
                $statShortlist->setCreated($time);
                $statShortlist->setStudent($studentProfile);
                $manager->persist($statShortlist);
            }
        }

        // Generate default member
        $member = new User();
        $member->setPlainPassword('123456');
        $member->setEmail('member@gs1au.org');
        $member->setEnabled(true);
        $member->setType(User::ROLE_GS1_MEMBER);
        $member->setFirstName($faker->firstName);
        $member->setLastName($faker->lastName);
        $manager->persist($member);

        // Generate 20 more accounts of students and members
        for($i=0; $i<20; $i++){
            $faker->seed(rand(($i+1)*10,($i+2)*10));

            $user = new User();
            $user->setPlainPassword('123456');
            $user->setEmail($faker->email);
            $user->setEnabled(true);
            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);

            $shouldBeAMember = $faker->numberBetween(0,2) == 0;
            if($shouldBeAMember){
                $user->setType(User::ROLE_GS1_MEMBER);
            }else{
                $user->setType(User::ROLE_STUDENT);

                // disable few students
                $shouldEnableStudent = $faker->numberBetween(0,2) != 0;
                $user->setStudentProfileVisibility($shouldEnableStudent ? User::VISIBILITY_VISIBLE : User::VISIBILITY_HIDDEN);

                $studentProfile =  new StudentProfile();
                $studentProfile->setContactEmail($user->getEmail());
                $studentProfile->setHeadline($faker->sentence);

                $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
                try {
                    $studentProfile->setContactNumber($phoneUtil->parse($faker->mobileNumber, "AU"));
                } catch (\libphonenumber\NumberParseException $e) {
                }

                $user->setStudentProfile($studentProfile);
            }
            $manager->persist($user);
        }

        $manager->flush();
    }
}