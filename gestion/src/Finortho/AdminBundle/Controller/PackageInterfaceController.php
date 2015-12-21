<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PackageInterfaceController extends Controller
{
    public function indexAction()
    {
        $entities = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Pack')->findAllPackNotCreatedByUser();
        return $this->render('FinorthoAdminBundle:Interface:content.html.twig', ['entities' => $entities]);
    }
}
