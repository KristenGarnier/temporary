<?php
/**
 * Created by PhpStorm.
 * User: PBW_12
 * Date: 03/06/2015
 * Time: 14:28
 */

namespace Finortho\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Finortho\Fritage\EchangeBundle\Entity\PackItem;
use Finortho\Fritage\EchangeBundle\Entity\PackProperty;
use Symfony\Component\HttpKernel\Exception\HttpException;


/**
 * Class for handling database call and logic form controller
 *
 * Class PackHandler
 * @package Finortho\ApiBundle\Handler
 */
class PackHandler
{

    /**
     * Doctrine entity manager
     * @var ObjectManager
     */
    private $om;
    /**
     * Name of the entity
     * @var
     */
    private $entityClass;
    /**
     * Rempository of the current entity
     * @var \Doctrine\Common\Persistence\ObjectRepository
     */
    private $repository;


    public function __construct(ObjectManager $om, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);

    }

    /**
     * Retrieve all products from user
     *
     * @param Int $id User id
     * @return array poducts from user
     * @throws HttpException if user does not exist
     */
    public function userPack($id)
    {
        return ['dummy data !'];
        //return $this->repository->findBy(array('utilisateur' => $id));
    }

    /**
     * Return an array with just the default entities
     *
     * @return array|\Finortho\Fritage\EchangeBundle\Entity\Pack[]
     */
    public function pack()
    {
        return $this->om->getRepository('FinorthoFritageEchangeBundle:Pack')->findAllPackNotCreatedByUser();
    }

    public function addPackUser($data, $id)
    {
        $pack = new PackItem();
        $category = $this->om->getRepository('FinorthoFritageEchangeBundle:Pack')->findOneByName($data['pack']);
        $propArray = [];
        foreach ($data['property'] as $i => $prop) {
            $packProp = $this->om->getRepository('FinorthoFritageEchangeBundle:PackProperty')->findOneBy(['name' => $prop, 'user' => $id]);
            if (empty($packProp)) {
                $packProp = new PackProperty();
                $packProp->setName($prop);
                $packProp->setUser($id);
            }
            array_push($propArray, $packProp);
        }

        $pack->setName($data['name']);
        $pack->setUser($id);
        $pack->setPack($category);

        foreach ($propArray as $p) {
            $pack->addProperty($p);
        }

        $this->om->persist($pack);
        $this->om->flush();
    }

}