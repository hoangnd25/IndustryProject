<?php

namespace App\ResumeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="student_access_code")
 * @UniqueEntity("code")
 */
class StudentAccessCode
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=16)
     * @Assert\Length(max = 16)
     */
    protected $code;

    /**
     * @ORM\OneToOne(targetEntity="App\UserBundle\Entity\User", inversedBy="activatedAccessCode", fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     **/
    protected $user;

    /**
     * @ORM\Column(type="boolean")
     **/
    protected $activated;

    public function __construct()
    {
        $this->activated = false;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->getCode();
    }


    /**
     * @return mixed
     */
    public function getCode()
    {
        return strtolower($this->code);
    }

    /**
     * @param mixed $code
     */
    public function setCode($code)
    {
        $this->code = strtolower($code);
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
    public function getActivated()
    {
        return $this->activated;
    }

    /**
     * @param mixed $activated
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;
    }
}