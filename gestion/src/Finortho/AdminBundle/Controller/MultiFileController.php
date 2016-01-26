<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Classe permettant d'accéder aux fichiers uploadésen multi
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
        $fileTree = $this->get('finortho_fritage_echange.file_tree');
        $tree = $fileTree->phpFilTree($this->get('kernel')->getRootDir() . '/../web/uploads/commandes_utilisateurs', "/uploads/commandes_utilisateurs/[link]");
        return $this->render('FinorthoAdminBundle:Default:index.html.twig', array('tree' => $tree));
    }

}
