<?php

namespace App\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="member_profile")
 */
class MemberProfile
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="memberProfile", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $user;

    /**
     * @ORM\Column(length=160)
     * @Assert\NotBlank()
     */
    protected $company;

    /**
     * @ORM\Column(length=160, nullable=true)
     */
    protected $number;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber()
     * @Assert\NotBlank()
     */
    protected $contactNumber;

    /**
     * @ORM\Column(length=4, nullable=true)
     * @Assert\Country()
     * @Assert\NotBlank()
     */
    protected $country;

    /**
     * @ORM\Column(length=20, nullable=true)
     * @Assert\Length(max="20")
     * @Assert\NotBlank()
     */
    protected $state;

    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->user ? $this->user->getFirstName() : "";
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        if($this->user){
            $this->user->setFirstName($firstName);
        }
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->user ? $this->user->getLastName() : "";
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        if($this->user){
            $this->user->setLastName($lastName);
        }
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * @param mixed $contactNumber
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;
    }

    /**
     * @return mixed
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param mixed $country
     */
    public function setCountry($country)
    {
        $this->country = $country;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->state = $state;
    }
}