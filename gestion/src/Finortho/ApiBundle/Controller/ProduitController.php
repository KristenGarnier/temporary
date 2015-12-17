<?php

namespace Finortho\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function getProduitsAction(Request $request)
    {
        if (array_key_exists('user', $request->headers) && $request->headers->get('user') != NULL) {
            return $this->get('produit_handler')->userProducts($request->headers->get('user'));
        } else {
            throw new \Exception('Access Forbidden', 403);
        }

    }
}
