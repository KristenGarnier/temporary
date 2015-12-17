<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * PackProperty
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @ExclusionPolicy("all")
 */
class PackProperty
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
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\PackItem", inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $packItem;

    



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
     * Set packItem
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\PackItem $packItem
     * @return PackProperty
     */
    public function setPackItem(\Finortho\Fritage\EchangeBundle\Entity\PackItem $packItem)
    {
        $this->packItem = $packItem;

        return $this;
    }

    /**
     * Get packItem
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\PackItem 
     */
    public function getPackItem()
    {
        return $this->packItem;
    }
}
