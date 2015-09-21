<?php

namespace App\UserBundle\DataFixtures\ORM;

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
        $admin->setUsername('admin');
        $admin->setPlainPassword('123456');
        $admin->setEmail('admin@gs1au.org');
        $admin->setEnabled(true);
        $admin->setType(User::ROLE_ADMIN);
        $manager->persist($admin);

        // Generate default student
        $student = new User();
        $student->setUsername('student');
        $student->setPlainPassword('123456');
        $student->setEmail('student@gs1au.org');
        $student->setEnabled(true);
        $student->setType(User::ROLE_STUDENT);
        $student->setStudentProfileVisibility(User::VISIBILITY_VISIBLE);

        $faker->seed(rand(1,10));
        $studentProfile = new StudentProfile();
        $studentProfile->setContactEmail($student->getEmail());
        $studentProfile->setFirstName($faker->firstName);
        $studentProfile->setLastName($faker->lastName);
        $studentProfile->setHeadline($faker->sentence);

        $student->setStudentProfile($studentProfile);
        $manager->persist($student);

        // Generate default member
        $member = new User();
        $member->setUsername('member');
        $member->setPlainPassword('123456');
        $member->setEmail('member@gs1au.org');
        $member->setEnabled(true);
        $member->setType(User::ROLE_GS1_MEMBER);
        $manager->persist($member);

        // Generate 20 more accounts of students and members
        for($i=0; $i<20; $i++){
            $faker->seed(rand(($i+1)*10,($i+2)*10));

            $user = new User();
            $user->setUsername($faker->userName);
            $user->setPlainPassword('123456');
            $user->setEmail($faker->email);
            $user->setEnabled(true);

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
                $studentProfile->setFirstName($faker->firstName);
                $studentProfile->setLastName($faker->lastName);
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