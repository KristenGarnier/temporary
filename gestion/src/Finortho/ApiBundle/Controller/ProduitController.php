<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;


/**
 * Api Class for Products
 *
 * Class ProduitController
 * @package Finortho\ApiBundle\Controller
 */
class ProduitController extends FOSRestController
{

    /**
     * Send an array of product from the current user ( id sended with the headers )
     *
     * @ApiDoc(
     *     resource=true,
     *  description="Get products just uploaded by the user",
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
    public function getProduitsAction(Request $request)
    {
        $user = $request->headers->get('user');
        if ($user != NULL) {
            return $this->get('getOr404')->check(
                $this->get('produit_handler'),
                'userProducts',
                $user
            );
        } else {
            throw new HttpException(403, "Access Forbidden");
        }

    }
}
