<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MultiUploadController extends Controller
{
    /**
     * Methode permettant d'afficher l'interface de multi upload
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(){
        $file_tree = $this->get('finortho_fritage_echange.file_tree');
        $tree = $file_tree->php_file_tree($this->get('kernel')->getRootDir() . '/../web/uploads/commandes_utilisateurs', "/uploads/commandes_utilisateurs/[link]" );
        return $this->render('FinorthoFritageEchangeBundle:Test:index.html.twig', array('tree' => $tree));
    }

    /**
     * Méthode permettant de notifier l'administrateur qu'une nouvelle commande est disponible
     *
     * @return JsonResponse
     */
    public function notifyAction(){

        $utilisateur = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:User')->find($this->getUser());

        $mailjet = $this->container->get('headoo_mailjet_wrapper');

        $params = array(
            "method" => "POST",
            "from" => "finortho@gmail.com",
            "to" => "frittage@finortho.com",
            "subject" => "Nouveaux fichiers importés sur le serveur",
            "html" => "<html>L'utilisateur : ".$utilisateur->getUsername()." a déposé des fichiers sur la plateforme de stockage.
            <br>
            <br>
            Email de l'utilisateur : ".$utilisateur->getEmail().'
            <br>
            <a href="http://212.47.229.9/admin"> Consulter les fichiers </a>
            </html>'
        );

        $result = $mailjet->sendEmail($params);

        return new JsonResponse(json_encode($result));
    }
}
