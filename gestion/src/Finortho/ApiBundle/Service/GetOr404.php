<?php

namespace Finortho\ApiBundle\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class GetOr404
 * @package Finortho\ApiBundle\Service
 */
class GetOr404
{
    /**
     * Check if user exists
     *
     * @var UserExist
     */
    private $userExist;

    public function __construct(UserExist $userExist)
    {
        $this->userExist = $userExist;
    }

    /**
     * If user exist return a value else throw an error
     *
     * @param Int $arg user id
     * @param $service
     * @param String $method name of the method
     * @param boolean $call If the function need to be called or not
     * @return array
     * @throws HttpException
     */
    public function check($service, $method, $arg, $call = true)
    {
        if ($this->userExist->check($arg)) {
            if($call){
                return call_user_func_array(array($service, $method), array($arg));
            }
            return true;
        }

        throw new HttpException(404, "User not found");
    }
}