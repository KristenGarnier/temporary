<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandeManagementController extends Controller
{
    public function indexAction()
    {
        $commands = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Commande')->findAll();
        return $this->render('FinorthoAdminBundle:Commande:index.html.twig', ['commandes' => $commands]);
    }
}
