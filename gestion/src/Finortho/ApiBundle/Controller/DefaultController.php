<?php

namespace Finortho\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FinorthoApiBundle:Default:index.html.twig', array('name' => $name));
    }
}
