<?php

namespace BrasserieBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use BrasserieBundle\Entity\Malt;
use BrasserieBundle\Form\MaltType;

/**
 * Malt controller.
 *
 * @Route("/malt")
 */
class MaltController extends Controller
{
    /**
     * Lists all Malt entities.
     *
     * @Route("/", name="malt")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('BrasserieBundle:Malt')->findBy([], ['nom' => 'ASC']);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Malt entity.
     *
     * @Route("/", name="malt_create")
     * @Method("POST")
     * @Template("BrasserieBundle:Malt:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Malt();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            $this->get('brasserie.file')->saveUploadedFiles($entity, 'malt', $entity->getNom());

            return $this->redirect($this->generateUrl('malt_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Malt entity.
     *
     * @param Malt $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Malt $entity)
    {
        $form = $this->createForm(new MaltType(), $entity, array(
            'action' => $this->generateUrl('malt_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Malt entity.
     *
     * @Route("/new", name="malt_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Malt();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Malt entity.
     *
     * @Route("/{id}", name="malt_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BrasserieBundle:Malt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Malt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Malt entity.
     *
     * @Route("/{id}/edit", name="malt_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BrasserieBundle:Malt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Malt entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        dump($entity);
        dump($entity->getFiles()->toArray());

        return array(
            'entity'        => $entity,
            'form'          => $editForm->createView(),
            'delete_form'   => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Malt entity.
    *
    * @param Malt $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Malt $entity)
    {
        $form = $this->createForm(new MaltType(), $entity, array(
            'action' => $this->generateUrl('malt_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Malt entity.
     *
     * @Route("/{id}", name="malt_update")
     * @Method("PUT")
     * @Template("BrasserieBundle:Malt:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('BrasserieBundle:Malt')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Malt entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            $this->get('brasserie.file')->saveUploadedFiles($entity, 'malt', $entity->getNom());

            //return $this->redirect($this->generateUrl('malt_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Malt entity.
     *
     * @Route("/{id}", name="malt_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('BrasserieBundle:Malt')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Malt entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('malt'));
    }

    /**
     * Creates a form to delete a Malt entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('malt_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
