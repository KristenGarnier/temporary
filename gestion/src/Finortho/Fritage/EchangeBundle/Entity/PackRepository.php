<?php

namespace Finortho\Fritage\EchangeBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\Common\Collections\ArrayCollection;

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

    public function findAllPackCreatedByUser($id)
    {


        $packs =  $this->getEntityManager()->getRepository('FinorthoFritageEchangeBundle:Pack')->findAll();
        foreach($packs as $pack){
            foreach($pack->getItems() as $item ){
                if($item->getUser() != $id){
                    $pack->removeItem($item);
                }
            }
            $pack->items = new ArrayCollection(array_values($pack->getItems()->toArray()));
        }
        return $packs;
    }
}
