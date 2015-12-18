<?php

namespace Finortho\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Finortho\Fritage\EchangeBundle\Entity\PackProperty;
use Finortho\Fritage\EchangeBundle\Form\PackPropertyType;

/**
 * PackProperty controller.
 *
 */
class PackagePropertyManagementController extends Controller
{

    /**
     * Lists all PackProperty entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->findAll();

        return $this->render('FinorthoAdminBundle:PackProperty:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Creates a new PackProperty entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PackProperty();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('packproperty_show', array('id' => $entity->getId())));
        }

        return $this->render('FinorthoAdminBundle:PackProperty:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a PackProperty entity.
     *
     * @param PackProperty $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PackProperty $entity)
    {
        $form = $this->createForm(new PackPropertyType(), $entity, array(
            'action' => $this->generateUrl('packproperty_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new PackProperty entity.
     *
     */
    public function newAction()
    {
        $entity = new PackProperty();
        $form = $this->createCreateForm($entity);

        return $this->render('FinorthoAdminBundle:PackProperty:new.html.twig', array(
            'entity' => $entity,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PackProperty entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackProperty entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:PackProperty:show.html.twig', array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PackProperty entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackProperty entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:PackProperty:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a PackProperty entity.
     *
     * @param PackProperty $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(PackProperty $entity)
    {
        $form = $this->createForm(new PackPropertyType(), $entity, array(
            'action' => $this->generateUrl('packproperty_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }

    /**
     * Edits an existing PackProperty entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackProperty entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('packproperty_edit', array('id' => $id)));
        }

        return $this->render('FinorthoFritageEchangeBundle:PackProperty:edit.html.twig', array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PackProperty entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackProperty')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PackProperty entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('packproperty'));
    }

    /**
     * Creates a form to delete a PackProperty entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('packproperty_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm();
    }

}