<?php

namespace Finortho\Fritage\EchangeBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Stl;
use Finortho\Fritage\EchangeBundle\Form\Type\StlType;
use Finortho\Fritage\EchangeBundle\Form\Type\StlModifType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class EchangeController
 *
 * Classe gérant tout le système d'échange avec l'utilisateur
 *
 * @package Finortho\Fritage\EchangeBundle\Controller
 */
class EchangeController extends Controller
{

    private $session;

    /**
     *
     */
    public function __construct()
    {
        $this->session = new Session();
    }

    /**
     * Methode permettant l'insertion basique d'un produit dans la base de donnée ( pas d'axe et pas de requirements )
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function indexAction(Request $request)
    {
        $session_handler = $this->get('finortho_fritage_echange.session_handler');
        $stl_file = new Stl();
        $form = $this->createForm(new StlType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {
            $checked = false;
            $form->handleRequest($request);
            if ($form->isValid()) {
                $double = $this->get('finortho_fritage_echange.check_double');
                try {
                    $double->check($stl_file->getName(), $this->getUser());

                } catch (\Exception $e) {

                    $currentCommand = $session_handler->getUploads($this->getUser()->getId());
                    $this->session->getFlashBag()->add('error', $e->getMessage());
                    return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView(), 'commands' => $currentCommand));
                }

                if ($request->get('same') == 'on') {
                    $params = $this->session->get('params');

                    if($request->get('axis') !== null && $request->get('axis') !== $stl_file->setAxis($params['axis'])){
                        $stl_file->setAxis($request->get('axis'));
                    }else {
                        $stl_file->setAxis($params['axis']);
                    }

                    if($request->get('quantite') !== null){
                        $stl_file->setQuantite($request->get('quantite'));
                    }else{
                        $stl_file->setQuantite($params['quantite']);
                    }

                    $checked = true;
                }

                $em = $this->getDoctrine()->getManager();
                $stl_file->setUtilisateur($this->getUser());
                $stl_file->setDate(new \DateTime());
                $stl_file->setName(
                    $this->get('finortho_fritage_echange.rename_file')
                        ->rename($stl_file->getName(), $this->getUser(), $stl_file->getQuantite())
                );
                $stl_file->preUpload();
                $stl_file->upload();

                $em->persist($stl_file);
                $em->flush();
            } else {

                $currentCommand = $session_handler->getUploads($this->getUser()->getId());

                $this->session->getFlashBag()->add('error', "Veuilez remplir le formulaire comme il le faut");


                return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView() , 'commands' => $currentCommand));
            }

            $session_handler->setUploads($stl_file);
            $name = $stl_file->getName() . '.' . $stl_file->getUrl();
            $this->session->getFlashBag()->add('success', "Le fichier ".$name." a bien été ajouté");

            if($checked){
                $currentCommand = $session_handler->getUploads($this->getUser()->getId());
                $this->session->set('params', array('axis' => $stl_file->getAxis(), 'quantite' => $stl_file->getQuantite(), 'fonctionnel' => $stl_file->getFonctionnel(), 'clinique' => $stl_file->getClinique(), 'verification' => $stl_file->getVerification3(), 'assemblage' => $stl_file->getAssemblage()));
                return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView(), 'commands' => $currentCommand));
            }
            return $this->redirect($this->generateUrl('finortho_fritage_axis_define' , array('id' => $stl_file->getId())));
        }

        $currentCommand = $session_handler->getUploads($this->getUser()->getId());
        return $this->render('FinorthoFritageEchangeBundle:fileUpload:index.html.twig', array('form' => $form->createView(), 'commands' => $currentCommand));
    }


    /**
     * Méthode permettant de forcer le téléchargment d'une pièce lors du click
     *
     * @param int $id identifiant de la pièce
     * @return Response
     */
    public function downloadAction($id)
    {

        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);

        $response = new Response();
        $response->setContent(file_get_contents($stl_file->getWebPath()));
        $response->headers->set('Content-Type', 'application/force-download'); // modification du content-type pour forcer le téléchargement (sinon le navigateur internet essaie d'afficher le document)
        $response->headers->set('Content-disposition', 'filename=' . $stl_file->getName() . '.' . $stl_file->getUrl());
        return $response;
    }

    /**
     * Méthode permettant de suprimmer une pièce de la base de donnée, et de suprimmer le fichier du serveur
     *
     * @param int $id identifiant de la pièce
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
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

    /**
     * Méthode permettant la modification des fichers uploadés
     *
     * @param Request $request
     * @param int $id id de la pièce à modifier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function modifyAction(Request $request, $id)
    {
        $stl_file = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Stl')->find($id);
        $name = $stl_file->getName();
        $path = $stl_file->getWebPath();
        $form = $this->createForm(new StlModifType(), $stl_file);

        if ($this->get('request')->getMethod() == 'POST') {


            $form->handleRequest($request);
            if ($form->isValid()) {

                if($name != $stl_file->getName()){
                    $stl_file->setName(
                        $stl_file->renomme(
                            $this->get('finortho_fritage_echange.rename_file')
                            ->rename($stl_file->getName(), $this->getUser(), $stl_file->getQuantite(), true)
                        )
                    );
                    rename($path, $stl_file->getWebPath());
                }

                $em = $this->getDoctrine()->getManager();
                $stl_file->setDate(new \DateTime());

                $em->flush();
            } else {

                $this->session->getFlashBag()->add('error', "Veuilez remplir le formulaire comme il le faut");

                return $this->render('FinorthoFritageEchangeBundle:fileUpload:modify.html.twig', array('form' => $form->createView()));
            }

            $this->session->getFlashBag()->add('success', "Le fichier a bien été modifié");
            return $this->redirect($this->generateUrl('finortho_fritage_echange_data'));

        }

        return $this->render('FinorthoFritageEchangeBundle:fileUpload:modify.html.twig', array('form' => $form->createView()));

    }

}
