<?php

namespace Finortho\ApiBundle\Service;



use Doctrine\Common\Persistence\ObjectManager;

class UserExist
{
    private $orm;

    public function __construct(ObjectManager $orm)
    {
        $this->orm = $orm;
    }

    public function check($id){
        return !empty(
            $this->orm->getRepository('FinorthoFritageEchangeBundle:User')->find($id)
        );
    }
}