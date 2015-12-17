<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * PackItem
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class PackItem
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @Expose
     */
    private $id;

    /**
     * @var string Variable contenant le nom du pack
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\Pack", inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $pack;

    /**
     * @ORM\OneToMany(targetEntity="Finortho\Fritage\EchangeBundle\Entity\PackProperty", mappedBy="pack")
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
     * Set pack
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\Pack $pack
     * @return PackItem
     */
    public function setPack(\Finortho\Fritage\EchangeBundle\Entity\Pack $pack)
    {
        $this->pack = $pack;

        return $this;
    }

    /**
     * Get pack
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\Pack 
     */
    public function getPack()
    {
        return $this->pack;
    }

    /**
     * Add items
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackProperty $items
     * @return PackItem
     */
    public function addItem(\Finortho\Fritage\EchangeBundle\Entity\PackProperty $items)
    {
        $this->items[] = $items;

        return $this;
    }

    /**
     * Remove items
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackProperty $items
     */
    public function removeItem(\Finortho\Fritage\EchangeBundle\Entity\PackProperty $items)
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
}
