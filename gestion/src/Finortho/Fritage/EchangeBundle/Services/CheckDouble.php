<?php

namespace Finortho\Fritage\EchangeBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;

/**
 * Class CheckDouble
 *
 * Service permettant de savoir si un produit existe déjà dans la base de donnée
 *
 * @package Finortho\Fritage\EchangeBundle\Services
 */
class CheckDouble
{
    private $em;

    /**
     * @param ObjectManager $em
     */
    public function __construct(ObjectManager $em)
    {
        $this->em = $em;
    }

    /**
     * Methode de vérification des doubles
     *
     * La méthode va chercher dans la base de donnée si le client a déjà rentré un fichier de ce nom pour cette entreprise
     * il peut y avoir plusieur entreprise avec le même nom de ficher
     *
     * @param $entityName nom de l'entité
     * @param $enterpriseName nom de l'entreprise
     * @throws \Exception si il existe déjà un fihcier sous ce nom
     *
     * @return boolean
     */
    public function check($entityName, $enterpriseName){
        $check = $this->em->getRepository('FinorthoFritageEchangeBundle:Stl')->findOneBy(array('name' => $entityName, 'utilisateur' => $enterpriseName));

        if( !empty($check)){
            Throw new \Exception('Il existe déjà un fichier sous ce nom');
        }

        return true;

    }
}