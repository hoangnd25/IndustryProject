<?php

namespace App\ResumeBundle\Entity;

use App\UserBundle\Entity\User;
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
     * @ORM\ManyToMany(targetEntity="App\ResumeBundle\Entity\Industry")
     * @ORM\JoinTable(name="student_industry_preference",
     *      joinColumns={@ORM\JoinColumn(name="student_profile_id", referencedColumnName="id", onDelete="CASCADE")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="industry_id", referencedColumnName="id", onDelete="CASCADE")}
     *      )
     **/
    protected $industryPreference;

    /**
     * @ORM\ManyToOne(targetEntity="App\ResumeBundle\Entity\EmploymentStatus")
     * @ORM\JoinColumn(name="employment_status_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $employmentStatus;

    /**
     * @ORM\OneToMany(targetEntity="App\ResumeBundle\Entity\StudentEducation", mappedBy="studentProfile", cascade={"persist"})
     **/
    protected $educations;

    /**
     * @ORM\OneToMany(targetEntity="App\ResumeBundle\Entity\StudentGS1Certification", mappedBy="student", cascade={"persist"})
     **/
    protected $gs1Certifications;

    /**
     * @ORM\OneToMany(targetEntity="App\ResumeBundle\Entity\StudentCertification", mappedBy="student", cascade={"persist"})
     **/
    protected $certifications;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $hasGs1Certification;

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
        $this->educations = new ArrayCollection();
        $this->gs1Certifications = new ArrayCollection();
        $this->certifications = new ArrayCollection();
        $this->industryPreference = new ArrayCollection();
        $this->hasGs1Certification = false;
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
     * @return mixed
     */
    public function getEducations()
    {
        return $this->educations;
    }

    /**
     * @param mixed $educations
     */
    public function setEducations($educations)
    {
        $this->educations = $educations;
    }

    /**
    * @param mixed $education
    */
    public function addEducations($education)
    {
        if($education != null && $education instanceof StudentEducation){
            $education->setStudentProfile($this);
        }
        $this->educations->add($education);
    }

    /**
     * @param mixed $education
     */
    public function removeEducations($education)
    {
        $this->educations->removeElement($education);
    }

    /**
     * @return mixed
     */
    public function getCertifications()
    {
        return $this->certifications;
    }

    /**
     * @param mixed $certifications
     */
    public function setCertifications($certifications)
    {
        $this->certifications = $certifications;
    }

    /**
     * @param mixed $cert
     */
    public function addCertifications($cert)
    {
        if($cert != null && $cert instanceof StudentCertification){
            $cert->setStudent($this);
        }
        $this->certifications->add($cert);
    }

    /**
     * @param mixed $cert
     */
    public function removeCertifications($cert)
    {
        $this->certifications->removeElement($cert);
    }

    /**
     * @return mixed
     */
    public function getGs1Certifications()
    {
        return $this->gs1Certifications;
    }

    /**
     * @param mixed $certs
     */
    public function setGs1Certifications($certs)
    {
        $this->gs1Certifications = $certs;
    }

    /**
     * @param mixed $cert
     */
    public function addGs1Certifications($cert)
    {
        if($cert != null && $cert instanceof StudentGS1Certification){
            $cert->setStudent($this);
        }
        $this->gs1Certifications->add($cert);
    }

    /**
     * @param mixed $cert
     */
    public function removeGs1Certifications($cert)
    {
        $this->gs1Certifications->removeElement($cert);
    }

    /**
     * @return mixed
     */
    public function getHasGs1Certification()
    {
        return $this->hasGs1Certification;
    }

    /**
     * @param mixed $hasGs1Certification
     */
    public function setHasGs1Certification($hasGs1Certification)
    {
        $this->hasGs1Certification = $hasGs1Certification;
    }

    /**
     * @return mixed
     */
    public function getIndustryPreference()
    {
        return $this->industryPreference;
    }

    /**
     * @param mixed $industryPreference
     */
    public function setIndustryPreference($industryPreference)
    {
        $this->industryPreference = $industryPreference;
    }

    /**
     * @param mixed $industry
     */
    public function addIndustryPreference($industry)
    {
        $this->industryPreference->add($industry);
    }

    /**
     * @param mixed $industry
     */
    public function removeIndustryPreference($industry)
    {
        $this->industryPreference->removeElement($industry);
    }

    /**
     * @return mixed
     */
    public function getEmploymentStatus()
    {
        return $this->employmentStatus;
    }

    /**
     * @param mixed $employmentStatus
     */
    public function setEmploymentStatus($employmentStatus)
    {
        $this->employmentStatus = $employmentStatus;
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