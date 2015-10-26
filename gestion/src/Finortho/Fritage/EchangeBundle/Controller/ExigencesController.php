<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Form\ExigenceType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ExigencesController extends Controller
{
    public function defineAction($id,Request $request)
    {
        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);
        $form = $this->createForm(new ExigenceType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $em->flush();
            }

            $params = $this->get('session')->get('params');
            $paramsMore = array('quantite' => $stl_file->getQuantite(), 'fonctionnel' => $stl_file->getFonctionnel(), 'clinique' => $stl_file->getClinique(), 'verification' => $stl_file->getVerification3(), 'assemblage' => $stl_file->getAssemblage());
            $final = array_merge($params, $paramsMore);
            $this->get('session')->set('params', $final);
            return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));
        }
        return $this->render('FinorthoFritageEchangeBundle:fileUpload:exigences.html.twig', array('form' => $form->createView()));
    }

    public function testAction(){

    }
}
