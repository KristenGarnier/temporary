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


    public function __construct(ObjectManager $om)
    {
        $this->om = $om;
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
        return $this->om->getRepository('FinorthoFritageEchangeBundle:Pack')->findAllPackCreatedByUser($id);
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
