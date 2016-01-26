<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Pack;
use Finortho\Fritage\EchangeBundle\Form\Type\PackType;

class PackageManagementController extends Controller
{
    /**
     * Lists all Pack entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FinorthoFritageEchangeBundle:Pack')->findAll();

        return $this->render('FinorthoAdminBundle:Pack:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Pack entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Pack();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('pack_show', array('id' => $entity->getId())));
        }

        return $this->render('FinorthoAdminBundle:Pack:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Pack entity.
     *
     * @param Pack $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Pack $entity)
    {
        $form = $this->createForm(new PackType(), $entity, array(
            'action' => $this->generateUrl('pack_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer', 'attr' => ['class' => 'btn start']));

        return $form;
    }

    /**
     * Displays a form to create a new Pack entity.
     *
     */
    public function newAction()
    {
        $entity = new Pack();
        $form   = $this->createCreateForm($entity);

        return $this->render('FinorthoAdminBundle:Pack:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Pack entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:Pack')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pack entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:Pack:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Pack entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:Pack')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pack entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:Pack:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a Pack entity.
     *
     * @param Pack $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Pack $entity)
    {
        $form = $this->createForm(new PackType(), $entity, array(
            'action' => $this->generateUrl('pack_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour', 'attr' => ['class' => 'btn start']));

        return $form;
    }
    /**
     * Edits an existing Pack entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:Pack')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Pack entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('pack_edit', array('id' => $id)));
        }

        return $this->render('FinorthoAdminBundle:Pack:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Pack entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FinorthoFritageEchangeBundle:Pack')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Pack entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('pack'));
    }

    /**
     * Creates a form to delete a Pack entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pack_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => ['class' => 'btn danger', 'style' => 'margin-top: 2rem; margin-left: 1vw;']))
            ->getForm()
            ;
    }
}
