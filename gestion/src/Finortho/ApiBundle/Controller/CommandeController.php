<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class MessageController
 * @package Finortho\ApiBundle\Controller
 */
class CommandeController extends FOSRestController
{
    /**
     * Attatch product to packitems
     *
     * @ApiDoc(
     *  resource= true,
     *  description="Register a command into the db",
     *  requirements={
     *      {
     *          "name"="packId",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="id of the chosen pack"
     *      },
     *     {
     *          "name"="category",
     *          "dataType"="string",
     *          "description"="Nome of the exigence cathegoty"
     *      },
     *     {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id ot the product"
     *      },
     *     {
     *          "name"="id",
     *          "dataType"="integer",
     *          "requirement"="\d+",
     *          "description"="Id ot the product"
     *     }
     *  },
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
     * @return \FOS\RestBundle\View\View
     */
    public function postCommandeAction(Request $request)
    {
        $user = $request->headers->get('user');
        if ($user != NULL) {
            if ($this->get('getOr404')->check(null, null, $user, false)) {
                $commande = $this->get('commande_handler')->attachProduct($request->request->all(), $user);

                $this->get('write_infos')->onCommand($this
                    ->getDoctrine()
                    ->getRepository('FinorthoFritageEchangeBundle:Commande')
                    ->find($commande)
                );

                $this->get('finortho_fritage_echange.email_admin')->sendAdminNotification(
                    $this->getDoctrine()
                        ->getRepository('FinorthoFritageEchangeBundle:User')
                        ->find($user),
                    $commande
                );

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
