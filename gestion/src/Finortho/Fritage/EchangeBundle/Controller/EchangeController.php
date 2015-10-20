<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Finortho\Fritage\EchangeBundle\Form\StlType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class EchangeController extends Controller
{

    public function indexAction(Request $request)
    {

        $session = new Session();
        if ($session->get('entreprise') != null) {
            $stl_file = new Stl();
            $form = $this->createForm(new StlType(), $stl_file);

            if ($this->get('request')->getMethod() == 'POST') {
                $form->handleRequest($request);
                if ($form->isValid()) {
                    $em = $this->getDoctrine()->getManager();
                    $stl_file->preUpload();
                    $stl_file->upload();

                    $em->persist($stl_file);
                    $em->flush();
                } else {
                    return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('name' => 'zerzerte', 'form' => $form->createView()));
                }

                return $this->render('FinorthoFritageEchangeBundle:fileUpload:success.html.twig', array('name' => $stl_file->getName()));
            }


            return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('name' => 'diamond', 'form' => $form->createView()));
        }else {
            return new $this->redirect($this->generateUrl('finortho_fritage_echange_identification_entreprise'));
        }


    }

    public function testAction()
    {
        return $this->render('FinorthoFritageEchangeBundle:Default:test.html.twig');
    }


}
