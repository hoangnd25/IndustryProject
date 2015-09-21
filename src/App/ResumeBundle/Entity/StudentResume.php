<?php

namespace App\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity
 * @ORM\Table(name="student_resume")
 * @Vich\Uploadable
 */
class StudentResume
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="App\ResumeBundle\Entity\StudentProfile", inversedBy="resume")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     **/
    protected $studentProfile;

    /**
     * @Vich\UploadableField(mapping="student_resume", fileNameProperty="fileName")
     * @var File
     */
    protected $file;

    /**
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    protected $fileName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $file
     */
    public function setFile(File $file = null)
    {
        $this->file = $file;

        if ($file) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @param string $fileName
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return StudentProfile
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

    public function getEncodedFile(){
        // This function must exist so mapping for elastic is correct
    }

    public function __construct()
    {
    }
}