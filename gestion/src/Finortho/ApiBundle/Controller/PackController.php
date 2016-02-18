<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * Api Class for Packs
 *
 * Class PackController
 * @package Finortho\ApiBundle\Controller
 */
class PackController extends FOSRestController
{

    /**
     * Send an array of packs from the current user ( id sended with the headers )
     *
     * @ApiDoc(
     *  description="Get all the packs created by user",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when the user is not allowed",
     *      404={
     *          "Returned when the user is not found",
     *      }
     * }
     * )
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
     * @ApiDoc(
     *  description="Get all default packs",
     *  statusCodes={
     *      200="Returned when successful",
     *      403="Returned when the user is not allowed",
     *      404={
     *          "Returned when the user is not found",
     *      }
     * }
     * )
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

    /**
     * Add a personal pack to the user
     * @ApiDoc(
     *     resource= true,
     *  description="Save a user personal pack into the db",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="Name given by the user for the pack"
     *      },
     *     {
     *          "name"="property",
     *          "dataType"="array",
     *          "description"="Array of string, representing the exigences for the product"
     *      },
     *     {
     *          "name"="pack",
     *          "dataType"="string",
     *          "description"="Name of the exigence catheory the pack belongs"
     *      }
     *  },
     *  statusCodes={
     *      201="Returned when successful",
     *      403="Returned when the user is not allowed",
     *      404={
     *          "Returned when the user is not found",
     *      }
     * }
     * )
     *
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postPacksAction(Request $request)
    {

        $user = $request->headers->get('user');

        if ($user != NULL) {
            if ($this->get('getOr404')->check(null, null, $user, false)) {
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
