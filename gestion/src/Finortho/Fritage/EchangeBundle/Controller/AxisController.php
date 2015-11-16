<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Form\AxisType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AxisController
 *
 * Classe de définition de l'axe de la pièce
 *
 * @package Finortho\Fritage\EchangeBundle\Controller
 */
class AxisController extends Controller
{
    /**
     * Méthode permettant de définir l'axe de la pièce dans la machine à l'aide d'un rendu avec plan 3D.
     *
     * @param integer $id de la pièce
     * @param Request $request données du formulaire
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
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
