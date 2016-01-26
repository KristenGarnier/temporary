<?php

namespace Finortho\ApiBundle\Service;



use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class UserExist
 * @package Finortho\ApiBundle\Service
 */
class UserExist
{
    /**
     * Reference to the doctrine API
     * @var ObjectManager
     */
    private $orm;

    public function __construct(ObjectManager $orm)
    {
        $this->orm = $orm;
    }

    /**
     * Check if the user exists
     * @param $id
     * @return bool
     */
    public function check($id){
        return !empty(
            $this->orm->getRepository('FinorthoFritageEchangeBundle:User')->find($id)
        );
    }
}
