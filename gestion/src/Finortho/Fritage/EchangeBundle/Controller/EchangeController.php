<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Finortho\Fritage\EchangeBundle\Form\StlType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;

class EchangeController extends Controller
{

    private $session;

    public function __construct(){
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        if (array_key_exists('entreprise', $this->session->all())) {
            $stl_file = new Stl();
            $form = $this->createForm(new StlType(), $stl_file);

            if ($this->get('request')->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $stl_file->setNameEntreprise($this->session->get('entreprise'));
                    $stl_file->setDate(new \DateTime());
                    $stl_file->preUpload();
                    $stl_file->upload();

                    $em->persist($stl_file);
                    $em->flush();
                } else {
                    return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView()));
                }

                $uploads = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->findByNameEntreprise($stl_file->getNameEntreprise());

                return $this->render('FinorthoFritageEchangeBundle:fileUpload:success.html.twig', array('path' => $stl_file->get3DPath(), 'name' => $stl_file->getName(), 'uploads' => $uploads));
            }


            return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView()));
        }

        return new $this->redirect($this->generateUrl('finortho_fritage_echange_identification_entreprise'));


    }

    public function connexionAction(Request $request)
    {
        if ($this->get('request')->getMethod() == 'POST') {
            $this->session->set('entreprise', $request->get('entreprise'));
            return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));
        }

        if(array_key_exists('entreprise', $this->session->all())){
            return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));
        }
        return $this->render('FinorthoFritageEchangeBundle:fileUpload:connexion.html.twig');

    }

    public function downloadAction($id){

        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);

        $response = new Response();
        $response->setContent(file_get_contents($stl_file->getWebPath()));
        $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', 'filename='. $stl_file->getName().'.'.$stl_file->getUrl());
        return $response;
    }
}