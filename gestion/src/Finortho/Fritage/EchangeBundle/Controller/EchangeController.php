<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EchangeController extends Controller
{
    public function indexAction()
    {
        return $this->render('FinorthoFritageEchangeBundle:Default:index.html.twig', array('name' => 'diamond'));
    }
}
