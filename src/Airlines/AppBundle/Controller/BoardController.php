<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Airlines\AppBundle\Entity\Board;
use Airlines\AppBundle\Form\BoardType;

/**
 * Board controller.
 *
 * @Route("/board")
 */
class BoardController extends Controller
{

    /**
     * Lists all Board entities.
     *
     * @Route("/", name="board")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AirlinesAppBundle:Board')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Board entity.
     *
     * @Route("/", name="board_create")
     * @Method("POST")
     * @Template("AirlinesAppBundle:Board:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Board();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('board'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Board entity.
     *
     * @param Board $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Board $entity)
    {
        $form = $this->createForm(new BoardType(), $entity, array(
            'action' => $this->generateUrl('board_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Board entity.
     *
     * @Route("/new", name="board_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Board();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Deletes a Board entity.
     *
     * @Route("/{id}", name="board_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AirlinesAppBundle:Board')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Board entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('board'));
    }

    /**
     * Creates a form to delete a Board entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('board_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }



    /**
     * Displays a board for given week in given years
     * Last two parameters are optional and default to current date
     *
     * @param Board $board Board instance
     * @param int   $week  Week number
     * @param int   $year  Year
     *
     * @return Response
     *
     * @Route("/{id}/{week}/{year}", name="board_show")
     * @Template("board.html.twig")
     */
    public function showAction(Board $board, $week = null, $year = null)
    {
        is_null($week) && $week = date('W');
        is_null($year) && $year = date('o');

        return compact('board', 'week', 'year');
    }
}
