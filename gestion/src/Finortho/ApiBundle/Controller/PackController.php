<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\Util\Codes;

/**
 * Api Class for Packs
 *
 * Class ProduitController
 * @package Finortho\ApiBundle\Controller
 */
class PackController extends FOSRestController
{

    /**
     * Send an array of packs from the current user ( id sended with the headers )
     *
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getPacksUserAction(Request $request)
    {
        $user = $request->headers->get('user');

        if ($user != NULL) {
            return $this->get('getOr404')->check(
                $this->get('pack_handler'),
                'userPack',
                $user
            );
        } else {
            throw new HttpException(403, "Access Forbidden");
        }

    }

    /**
     * Send back default pack
     *
     * @param Request $request
     * @return mixed
     * @throws \HttpException
     */
    public function getPacksAction(Request $request)
    {
        $user = $request->headers->get('user');

        if ($user != NULL) {
            return $this->get('getOr404')->check(
                $this->get('pack_handler'),
                'pack',
                $user
            );
        } else {
            throw new HttpException(403, "Access Forbidden");
        }

    }

    public function postPacksAction(Request $request){

        $user = $request->headers->get('user');

        if ($user != NULL) {
            if ($this->get('getOr404')->check(null, null, $user, false)){
                $this->get('pack_handler')->addPackUser($request->request->all(), $user);

                $routeOptions = array(
                    '_format' => $request->get('_format')
                );

                return $this->view(null, Codes::HTTP_CREATED, $routeOptions);
            }
        } else {
            throw new HttpException(403, "Access Forbidden");
        }
    }

}
