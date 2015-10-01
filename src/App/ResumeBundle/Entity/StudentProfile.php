<?php

namespace App\ResumeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Misd\PhoneNumberBundle\Validator\Constraints\PhoneNumber as AssertPhoneNumber;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="student_profile")
 */
class StudentProfile
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\NotBlank()
     */
    protected $firstName;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\NotBlank()
     */
    protected $lastName;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="studentProfile", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $user;

    /**
     * @ORM\Column(length=120, nullable=true)
     * @Assert\Length(max="120")
     * @Assert\NotBlank()
     */
    protected $headline;

    /**
     * @ORM\Column(length=250, nullable=true)
     * @Assert\Length(max="250")
     */
    protected $about;

    /**
     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\StudentResume", mappedBy="studentProfile", cascade={"persist"})
     **/
    protected $resume;

    /**
     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\StudentAvatar", mappedBy="studentProfile", cascade={"persist"})
     **/
    protected $avatar;

    /**
     * @ORM\Column(type="phone_number", nullable=true)
     * @AssertPhoneNumber()
     * @Assert\NotBlank()
     */
    protected $contactNumber;

    /**
     * @ORM\Column(length=120, nullable=true)
     * @Assert\Length(max="120")
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    protected $contactEmail;

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

    /**
     * @ORM\Column(length=40, nullable=true)
     * @Assert\Length(max="40")
     * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $workingRight;

    /**
     * @ORM\OneToMany(targetEntity="App\ResumeBundle\Entity\StudentSocialNetwork", mappedBy="student", cascade={"persist"}, fetch="EXTRA_LAZY")
     **/
    protected $socialNetworks;

    /**
     * @var \DateTime $updated
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated;

    public function __construct()
    {
        $this->updated = new \DateTime();
        $this->socialNetworks = new ArrayCollection();
        $this->workingRight = false;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * @param mixed $headline
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * @return mixed
     */
    public function getAbout()
    {
        return $this->about;
    }

    /**
     * @param mixed $about
     */
    public function setAbout($about)
    {
        $this->about = $about;
    }

    /**
     * @return string | null
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string | null
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getFullName(){
        return trim($this->getFirstName() . " " . $this->getLastName());
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
     * @return StudentResume
     */
    public function getResume()
    {
        return $this->resume;
    }

    /**
     * @param mixed $resume
     */
    public function setResume($resume)
    {
        $this->resume = $resume;
        $this->setUpdated(new \DateTime());
        if($this->resume != null && $this->resume instanceof StudentResume){
            $this->resume->setStudentProfile($this);
        }
    }

    /**
     * @return StudentAvatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param mixed $avatar
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;
        $this->setUpdated(new \DateTime());
        if($this->avatar != null && $this->avatar instanceof StudentAvatar){
            $this->avatar->setStudentProfile($this);
        }
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
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * @param mixed $contactEmail
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
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

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getWorkingRight()
    {
        return $this->workingRight;
    }

    /**
     * @param mixed $workingRight
     */
    public function setWorkingRight($workingRight)
    {
        $this->workingRight = $workingRight;
    }

    /**
     * @return mixed
     */
    public function getSocialNetworks()
    {
        return $this->socialNetworks;
    }

    /**
     * @param mixed $socialNetworks
     */
    public function setSocialNetworks($socialNetworks)
    {
        $this->socialNetworks = $socialNetworks;
    }

    /**
     * @param mixed $socialNetworks
     */
    public function addSocialNetworks($socialNetworks)
    {
        if($socialNetworks != null && $socialNetworks instanceof StudentSocialNetwork){
            $socialNetworks->setStudent($this);
        }
        $this->socialNetworks->add($socialNetworks);
    }

    /**
     * @param mixed $socialNetworks
     */
    public function removeSocialNetworks($socialNetworks)
    {
        $this->socialNetworks->removeElement($socialNetworks);
    }

    /**
     * @return \DateTime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;
    }
}