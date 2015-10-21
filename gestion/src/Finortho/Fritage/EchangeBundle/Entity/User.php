<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="tarif", type="string", length=255)
     */
    protected $tarif = 1;



    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    /**
     * Set tarif
     *
     * @param string $tarif
     * @return User
     */
    public function setTarif($tarif)
    {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return string 
     */
    public function getTarif()
    {
        return $this->tarif;
    }

}
