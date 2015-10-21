<?php

namespace App\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="config_employment_status")
 */
class EmploymentStatus
{

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(length=160)
     */
    protected $name;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $noticeRequired;

    public function __construct($name = null, $noticeRequired = false)
    {
        $this->name = $name;
        $this->noticeRequired = $noticeRequired;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->name;
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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function isNoticeRequired()
    {
        return $this->noticeRequired;
    }

    /**
     * @param mixed $noticeRequired
     */
    public function setNoticeRequired($noticeRequired)
    {
        $this->noticeRequired = $noticeRequired;
    }

}