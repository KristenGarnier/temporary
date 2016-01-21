<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CommandeManagementController extends Controller
{
    public function indexAction()
    {
        $commands = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Commande')->findBy(['completed' => false], ['date' => 'DESC']);
        return $this->render('FinorthoAdminBundle:Commande:index.html.twig', ['commandes' => $commands]);
    }

    public function completedAction(){
        $commands = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Commande')->findBy(['completed' => true], ['date' => 'DESC']);
        $serializer = $this->container->get('serializer');
        return $this->render('FinorthoAdminBundle:Commande:completed.html.twig', ['commandes' => $serializer->serialize($commands, 'json')]);
    }

    public function showAction($id){
        $commande = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Commande')->find($id);
        return $this->render('FinorthoAdminBundle:Commande:single.html.twig', ['commande' => $commande]);
    }

    public function markCompleteAction($id){
        $commande = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Commande')->find($id);
        $commande->setCompleted(true);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($commande);
        $em->flush();

        return $this->redirect($this->generateUrl('finortho_admin_commandes'));
    }
}
