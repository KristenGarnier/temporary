<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MultiUploadController extends Controller
{
    /**
     * Methode permettant d'afficher l'interface de multi upload
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){
        $file_tree = $this->get('finortho_fritage_echange.file_tree');
        $tree = $file_tree->phpFileTree($this->get('kernel')->getRootDir() . '/../web/uploads/commandes_utilisateurs', "/uploads/commandes_utilisateurs/[link]" );
        return $this->render('FinorthoFritageEchangeBundle:Test:index.html.twig', array('tree' => $tree));
    }
}
