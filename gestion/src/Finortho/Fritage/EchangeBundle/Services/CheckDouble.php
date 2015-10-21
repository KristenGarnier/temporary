<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\ORM\EntityManager;

class CheckDouble
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function check($entityName, $enterpriseName){
        $check = $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->findOneBy(array('name' => $entityName, 'nameEntreprise' => $enterpriseName));

        if( !empty($check)){
            Throw new \Exception('Il existe déjà un fichier sous ce nom');
        }

    }
}