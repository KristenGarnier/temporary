<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Form\AxisType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AxisController extends Controller
{
    public function defineAction($id,Request $request)
    {
        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);
        $form = $this->createForm(new AxisType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->flush();
            } else {

                $this->session->getFlashBag()->add('error', "Veuilez remplir le formulaire comme il le faut");
                return $this->render('FinorthoFritageEchangeBundle:fileUpload:axis.html.twig', array('form' => $form->createView(), 'path' => $stl_file->get3DPath()));

            }
            $this->get('session')->set('params', array('axis'=> $stl_file->getAxis()));
            return $this->redirect($this->generateUrl('finortho_fritage_exigences_define', array('id' => $stl_file->getId())));
        }
        return $this->render('FinorthoFritageEchangeBundle:fileUpload:axis.html.twig', array('form' => $form->createView(), 'path' => $stl_file->get3DPath()));
    }
}
