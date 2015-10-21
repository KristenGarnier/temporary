<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class LastUploads
{
    private $em;
    private $user;

    public function __construct(EntityManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->user = $token->getToken()->getUser();
    }

    public function get(){
        $uploads = $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->findByNameEntreprise($this->user->getUsername());
        return $uploads;
    }
}