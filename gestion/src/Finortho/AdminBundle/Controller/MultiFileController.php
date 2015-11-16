<?php

namespace Finortho\AdminBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class MultiFileController
 * @package Finortho\AdminBundle\Controller
 */
class MultiFileController extends Controller
{


    /**
     * Méthode de génération de l'index avec l'arbre des fichiers du multi
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $file_tree = $this->get('finortho_fritage_echange.file_tree');
        $tree = $file_tree->php_file_tree($this->get('kernel')->getRootDir() . '/../web/uploads/commandes_utilisateurs', "/uploads/commandes_utilisateurs/[link]");
        return $this->render('FinorthoAdminBundle:Default:index.html.twig', array('tree' => $tree));
    }

}
