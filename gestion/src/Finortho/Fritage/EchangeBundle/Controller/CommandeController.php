<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CommandeController extends Controller
{
    public function indexAction()
    {
        $uploads = $this->get('finortho_fritage_echange.session_handler')->getUploads();
        return $this->render('FinorthoFritageEchangeBundle:Commande:index.html.twig', array('uploads' => $uploads));
    }
}
