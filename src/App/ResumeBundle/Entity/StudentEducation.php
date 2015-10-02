<?php

namespace App\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="student_education")
 */
class StudentEducation
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\ResumeBundle\Entity\StudentProfile", inversedBy="educations")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     **/
    protected $studentProfile;

    /**
     * @ORM\ManyToOne(targetEntity="App\ResumeBundle\Entity\Institution")
     * @ORM\JoinColumn(name="institution_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $institution;

    /**
     * @ORM\Column(length=260)
     */
    protected $degree;

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
     * @return mixed
     */
    public function getStudentProfile()
    {
        return $this->studentProfile;
    }

    /**
     * @param mixed $studentProfile
     */
    public function setStudentProfile($studentProfile)
    {
        $this->studentProfile = $studentProfile;
    }

    /**
     * @return mixed
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param mixed $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return mixed
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * @param mixed $degree
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
    }

}