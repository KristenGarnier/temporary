<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PackRepository extends EntityRepository
{
    public function findAllPackNotCreatedByUser()
    {
        $packs =  $this->getEntityManager()->getRepository('FinorthoFritageEchangeBundle:Pack')->findAll();
        foreach($packs as $pack){
            foreach($pack->getItems() as $item){
                if($item->getUser() != NULL){
                    $pack->removeItem($item);
                }
            }
        }

        return $packs;
    }
}