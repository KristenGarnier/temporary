<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

/**
 * Class LastUploads
 *
 * Classe permettant de récupérer les derniers uploads d'un utilisateur
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class LastUploads
{
    /**
     * @var EntityManager
     */
    private $em;
    /**
     * @var mixed
     */
    private $user;

    /**
     * @param ObjectManager $em
     * @param TokenStorage  $token
     */
    public function __construct(ObjectManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->user = $token->getToken()->getUser();
    }

    /**
     * Méthode permettant d'aller chercher les uploads de l'utilisateur connecté
     *
     * @return mixed
     */
    public function get(){
        $uploads = $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->findBy(array('user' => $this->user), array('date' => 'DESC'));
        return $uploads;
    }
}
