<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PackageInterfaceController extends Controller
{
    public function indexAction()
    {
        return $this->render('FinorthoAdminBundle:Interface:index.html.twig');
    }
}
