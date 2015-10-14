<?php

namespace App\ContentBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Rollerworks\Bundle\PasswordStrengthBundle\Validator\Constraints as RollerworksPassword;

/**
 * @ORM\Entity
 * @ORM\Table(name="cms_menu")
 */
class Menu
{
    const CACHE_PREFIX = 'menu_';

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
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="App\ContentBundle\Entity\MenuItem", mappedBy="menu", cascade={"persist"}, orphanRemoval=true, fetch="EXTRA_LAZY")
     **/
    protected $items;

    public function __construct($id = null, $name = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->items = new ArrayCollection();
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->getName();
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
     * @return ArrayCollection
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     */
    public function setItems($items)
    {
        $this->items = $items;
    }

    /**
     * @param mixed $item
     */
    public function addItems($item)
    {
        if($item instanceof MenuItem){
            $item->setMenu($this);
        }
        $this->items->add($item);
    }

    /**
     * @param mixed $item
     */
    public function removeItems($item)
    {
        $this->items->removeElement($item);
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
}