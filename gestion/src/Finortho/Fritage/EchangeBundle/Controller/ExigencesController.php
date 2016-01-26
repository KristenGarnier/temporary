<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ExigencesController
 *
 * Classe permettant de définir les éxigences par rapport à un produit
 *
 * @package Finortho\Fritage\EchangeBundle\Controller
 */
class ExigencesController extends Controller
{
    /**
     * Methode permettant de définir les exigeances pour chaque produit
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function defineAction(Request $request)
    {
        return $this->render('FinorthoFritageEchangeBundle:Exigence:index.html.twig', ['user' => $this->getUser()->getId()]);
    }
}
