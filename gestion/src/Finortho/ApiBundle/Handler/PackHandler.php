<?php
/**
 * Created by PhpStorm.
 * User: PBW_12
 * Date: 03/06/2015
 * Time: 14:28
 */

namespace Finortho\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Finortho\ApiBundle\Service\UserExist;
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

    public function pack()
    {
        return ['here is yo pack'];
    }

}