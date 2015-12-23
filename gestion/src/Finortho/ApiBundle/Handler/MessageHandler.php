<?php
/**
 * Created by PhpStorm.
 * User: PBW_12
 * Date: 03/06/2015
 * Time: 14:28
 */

namespace Finortho\ApiBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Finortho\Fritage\EchangeBundle\Entity\Message;


/**
 * Class for handling database call and logic form controller
 *
 * Class MessageHandler
 * @package Finortho\ApiBundle\Handler
 */
class MessageHandler
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
     * Retrieve all message from user
     *
     * @param Int $id User id
     * @return array poducts from user
     * @throws HttpException if user does not exist
     */
    public function userMessages($id)
    {
        return $this->repository->findBy(array('user' => $id), ['id' => 'DESC']);
    }

    /**
     * Add a message and save it to the dab
     *
     * @param Int $id User id
     * @param string $data Message and when he has ben sent
     * @return array poducts from user
     * @throws HttpException if user does not exist
     */
    public function addUserMessage($id, $data)
    {
        $message = new Message();
        $message->setUser($id);
        $message->setText($data['text']);
        $message->setDate($data['date']);
        $message->setTime($data['time']);

        $this->om->persist($message);
        $this->om->flush();
    }
}