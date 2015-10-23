<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Finortho\Fritage\EchangeBundle\Form\StlType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class EchangeController extends Controller
{

    private $session;

    public function __construct()
    {
        $this->session = new Session();
    }

    public function indexAction(Request $request)
    {
        $session_handler = $this->get('finortho_fritage_echange.session_handler');
        $stl_file = new Stl();
        $form = $this->createForm(new StlType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $double = $this->get('finortho_fritage_echange.check_double');
                try {
                    $double->check($stl_file->getName(), $this->getUser());

                } catch (\Exception $e) {

                    $currentCommand = $session_handler->getUploads();
                    $this->session->getFlashBag()->add('error', $e->getMessage());
                    return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView(), 'commands' => $currentCommand));
                }

                $em = $this->getDoctrine()->getManager();
                $stl_file->setUtilisateur($this->getUser());
                $stl_file->setDate(new \DateTime());
                $stl_file->preUpload();
                $stl_file->upload();

                $em->persist($stl_file);
                $em->flush();
            } else {

                $currentCommand = $session_handler->getUploads();

                $this->session->getFlashBag()->add('error', "Veuilez remplir le formulaire comme il le faut");


                return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView() , 'commands' => $currentCommand));
            }

            $session_handler->setUploads($stl_file);
            $name = $stl_file->getName() . '.' . $stl_file->getUrl();
            $this->session->getFlashBag()->add('success', "Le fichier ".$name." a bien été ajouté");
            $currentCommand = $session_handler->getUploads();
            return $this->render('FinorthoFritageEchangeBundle:fileUpload:axis.html.twig', array( 'name' => $stl_file->getName(), 'form' => $form->createView(), 'commands' => $currentCommand));
        }

        $currentCommand = $session_handler->getUploads();
        return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView(), 'commands' => $currentCommand));
    }


    public function downloadAction($id)
    {

        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);

        $response = new Response();
        $response->setContent(file_get_contents($stl_file->getWebPath()));
        $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', 'filename=' . $stl_file->getName() . '.' . $stl_file->getUrl());
        return $response;
    }

    public function eraseAction($id)
    {
        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);
        $this->get('finortho_fritage_echange.session_handler')->unsetUpload($id);
        $em = $this->getDoctrine()->getEntityManager();

        $path = $stl_file->getWebPath();
        $name = $stl_file->getName() . '.' . $stl_file->getUrl();

        $em->remove($stl_file);
        $em->flush();

        unlink($path);

        $this->session->getFlashBag()->add('erase', "Le ficher $name a bien été suprimmé");

        return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));
    }

    public function modifyAction(Request $request, $id)
    {
        $session_handler = $this->get('finortho_fritage_echange.session_handler');
        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);
        $name = $stl_file->getName();
        $path = $stl_file->getWebPath();
        $form = $this->createForm(new StlType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {

            $user_uploads = $this->get('finortho_fritage_echange.user_uploads');

            $form->handleRequest($request);
            if ($form->isValid()) {

                if($name != $stl_file->getName() && $form['file']->getData() == null){
                    rename($path, $stl_file->getWebPath());
                }

                $em = $this->getDoctrine()->getManager();
                $stl_file->setDate(new \DateTime());
                $stl_file->preUpload();
                $stl_file->upload();

                $em->persist($stl_file);
                $em->flush();
            } else {

                $this->session->getFlashBag()->add('error', "Veuilez remplir le formulaire comme il le faut");

                return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView()));
            }

            $this->session->getFlashBag()->add('success', "Le fichier a bien été modifié");
            return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));

        }

        return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView()));

    }

}