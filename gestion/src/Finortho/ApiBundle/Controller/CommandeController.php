<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Util\Codes;
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
     * @param Request $request
     * @return \FOS\RestBundle\View\View
     */
    public function postCommandeAction(Request $request)
    {
        $user = $request->headers->get('user');
        if ($user != NULL) {
            if ($this->get('getOr404')->check(null, null, $user, false)) {
                $commande = $this->get('commande_handler')->attachProduct($request->request->all(), $user);

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
