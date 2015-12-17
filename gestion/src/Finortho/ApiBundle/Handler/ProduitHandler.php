<?php
/**
 * Created by PhpStorm.
 * User: PBW_12
 * Date: 03/06/2015
 * Time: 14:28
 */

namespace Finortho\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class for handling database call and logic form controller
 *
 * Class ProduitHandler
 * @package Finortho\ApiBundle\Handler
 */
class ProduitHandler
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
     */
    public function userProducts($id)
    {
        return $this->repository->findBy(array('utilisateur' => $id));
    }

}