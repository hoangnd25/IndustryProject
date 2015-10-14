<?php

namespace App\ContentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;

/**
 * @ORM\Entity
 * @ORM\Table(name="cms_content")
 */
class Content
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     */
    protected $id;

    /**
     * @ORM\Column(length=100, nullable=true)
     * @Assert\Length(max="100")
     * @Assert\NotBlank()
     */
    protected $title;

    /**
     * @ORM\Column(type="text")
     */
    protected $body;

    public function __construct($id = null)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
}