<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('FinorthoAdminBundle:Default:index.html.twig', array('name' => $name));
    }
}
