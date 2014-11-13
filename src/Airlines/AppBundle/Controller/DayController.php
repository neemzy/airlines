<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Airlines\AppBundle\Entity\Day;
use Airlines\AppBundle\Form\DayType;

/**
 * Day controller.
 *
 * @Route("/day")
 */
class DayController extends Controller
{

    /**
     * Lists all Day entities.
     *
     * @Route("/", name="day")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AirlinesAppBundle:Day')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Day entity.
     *
     * @Route("/", name="day_create")
     * @Method("POST")
     * @Template("AirlinesAppBundle:Day:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Day();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('day_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Day entity.
     *
     * @param Day $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Day $entity)
    {
        $form = $this->createForm(new DayType(), $entity, array(
            'action' => $this->generateUrl('day_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Day entity.
     *
     * @Route("/new", name="day_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Day();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Day entity.
     *
     * @Route("/{id}", name="day_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AirlinesAppBundle:Day')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Day entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Day entity.
     *
     * @Route("/{id}/edit", name="day_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AirlinesAppBundle:Day')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Day entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Day entity.
    *
    * @param Day $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Day $entity)
    {
        $form = $this->createForm(new DayType(), $entity, array(
            'action' => $this->generateUrl('day_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Day entity.
     *
     * @Route("/{id}", name="day_update")
     * @Method("PUT")
     * @Template("AirlinesAppBundle:Day:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AirlinesAppBundle:Day')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Day entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('day_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Day entity.
     *
     * @Route("/{id}", name="day_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AirlinesAppBundle:Day')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Day entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('day'));
    }

    /**
     * Creates a form to delete a Day entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('day_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
