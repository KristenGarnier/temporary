<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * Pack
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class Pack
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var string Variable contenant le nom du pack
     *
     * @ORM\Column(name="name", type="string", length=255)
     *@Expose
     */
    private $name;

    /**
     * @ORM\OneToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\User", cascade={"remove"})
     * @ORM\Column(nullable=true)
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="Finortho\Fritage\EchangeBundle\Entity\PackItem", mappedBy="pack")
     *
     * @Expose
     */
    private $items;



    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Pack
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add items
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackItem $items
     * @return Pack
     */
    public function addItem(\Finortho\Fritage\EchangeBundle\Entity\PackItem $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackItem $items
     */
    public function removeItem(\Finortho\Fritage\EchangeBundle\Entity\PackItem $items)
    {
        $this->items->removeElement($items);
    }

    /**
     * Get items
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * Set user
     *
     * @param string $user
     * @return Pack
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string 
     */
    public function getUser()
    {
        return $this->user;
    }
}
