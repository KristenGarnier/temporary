<?php

namespace Finortho\ApiBundle\Service;

use Symfony\Component\HttpKernel\Exception\HttpException;

class GetOr404
{
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
     * @return array
     * @throws HttpException
     */
    public function check($service, $method, $arg)
    {
        if ($this->userExist->check($arg)) {
            return call_user_func_array(array($service, $method), array($arg));
        }

        throw new HttpException(404, "User not found");
    }
}