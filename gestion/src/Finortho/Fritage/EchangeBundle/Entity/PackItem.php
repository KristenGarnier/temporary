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
     *
     */
    private $id;

    /**
     * @var string Variable contenant le nom du pack
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Expose
     *
     */
    private $name;


    /**
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\Pack", inversedBy="items")
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     *
     *
     */
    private $pack;

    /**
     * @ORM\ManyToMany(targetEntity="Finortho\Fritage\EchangeBundle\Entity\PackProperty", cascade={"detach"})
     * @Expose
     */
    public $property;

    /**
     * @var array
     * @Expose
     */
    private $childs = [];





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
        $this->property = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add property
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackProperty $property
     * @return PackItem
     */
    public function addProperty(\Finortho\Fritage\EchangeBundle\Entity\PackProperty $property)
    {
        $this->property[] = $property;

        return $this;
    }

    /**
     * Remove property
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackProperty $property
     */
    public function removeProperty(\Finortho\Fritage\EchangeBundle\Entity\PackProperty $property)
    {
        $this->property->removeElement($property);
    }

    /**
     * Get property
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getProperty()
    {
        return $this->property;
    }
}
