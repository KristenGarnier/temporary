<?php

namespace Finortho\AdminBundle\Controller;

use Finortho\Fritage\EchangeBundle\Entity\Pack;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Finortho\Fritage\EchangeBundle\Entity\PackItem;
use Finortho\Fritage\EchangeBundle\Form\PackItemType;

class PackageItemManagementController extends Controller
{
    /**
     * Lists all PackItem entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->findBy(['user' => NULL]);

        return $this->render('FinorthoAdminBundle:PackItem:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new PackItem entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new PackItem();
        $pack = $this->getDoctrine()->getRepository('FinorthoFritageEchangeBundle:Pack')->find(3);
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('packitem_show', array('id' => $entity->getId())));
        }

        return $this->render('FinorthoAdminBundle:PackItem:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a PackItem entity.
     *
     * @param PackItem $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(PackItem $entity)
    {
        $form = $this->createForm(new PackItemType(), $entity, array(
            'action' => $this->generateUrl('packitem_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Créer', 'attr' => ['class' => 'btn start']));

        return $form;
    }

    /**
     * Displays a form to create a new PackItem entity.
     *
     */
    public function newAction()
    {
        $entity = new PackItem();
        $form   = $this->createCreateForm($entity);

        return $this->render('FinorthoAdminBundle:PackItem:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PackItem entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:PackItem:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PackItem entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackItem entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('FinorthoAdminBundle:PackItem:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a form to edit a PackItem entity.
     *
     * @param PackItem $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(PackItem $entity)
    {
        $form = $this->createForm(new PackItemType(), $entity, array(
            'action' => $this->generateUrl('packitem_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Mettre à jour', 'attr' => ['class' => 'btn start']));

        return $form;
    }
    /**
     * Edits an existing PackItem entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PackItem entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('packitem_edit', array('id' => $id)));
        }

        return $this->render('FinorthoAdminBundle:PackItem:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a PackItem entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('FinorthoFritageEchangeBundle:PackItem')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PackItem entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('packitem'));
    }

    /**
     * Creates a form to delete a PackItem entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('packitem_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete', 'attr' => ['class' => 'btn danger', 'style' => 'margin-top: 2rem; margin-left: 1vw;']))
            ->getForm()
            ;
    }
}
