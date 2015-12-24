<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * Commande
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Commande
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var $utilisateur User attaché à la piece
     *
     * @ORM\ManyToOne(targetEntity="Finortho\Fritage\EchangeBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var $date Date date de la commande
     *
     * @ORM\Column(name="date", nullable= false)
     */
    private $date;

    /**
     * @var $stls Stl Tous les produits de la commande
     *
     * @ORM\OneToMany(targetEntity="Finortho\Fritage\EchangeBundle\Entity\Stl", mappedBy="commande")
     * @ORM\JoinColumn(nullable=true)
     */
    private $stls;


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
     * Constructor
     */
    public function __construct()
    {
        $this->stls = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set date
     *
     * @param string $date
     * @return Commande
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set user
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\User $user
     * @return Commande
     */
    public function setUser(\Finortho\Fritage\EchangeBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Finortho\Fritage\EchangeBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Add stls
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\Stl $stls
     * @return Commande
     */
    public function addStl(\Finortho\Fritage\EchangeBundle\Entity\Stl $stls)
    {
        $this->stls[] = $stls;

        return $this;
    }

    /**
     * Remove stls
     *
     * @param \Finortho\Fritage\EchangeBundle\Entity\Stl $stls
     */
    public function removeStl(\Finortho\Fritage\EchangeBundle\Entity\Stl $stls)
    {
        $this->stls->removeElement($stls);
    }

    /**
     * Get stls
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStls()
    {
        return $this->stls;
    }
}
