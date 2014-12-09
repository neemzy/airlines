<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Airlines\AppBundle\Entity\Task;
use Airlines\AppBundle\Entity\Member;

/**
 * JSON API Task management controller
 *
 * @Route("/api/tasks")
 */
class JsonTaskController extends Controller
{
    /**
     * Returns a JSON response
     *
     * @param mixed $data Data to serialize
     * @param int   $code HTTP response code
     *
     * @return Response
     */
    private function createJsonResponse($data, $code = Response::HTTP_OK)
    {
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($data, 'json');

        return new Response($json, $code, ['content-type' => 'application/json']);
    }



    /**
     * Returns a "No Content" (204) response
     *
     * @return Response
     */
    private function createNoContentResponse()
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }



    /**
     * Fetches a task
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="tasks.get", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function getAction(Task $task)
    {
        return $this->createJsonResponse($task);
    }




    /**
     * Fetches all tasks for the given Member and date
     *
     * @param Member   $member
     * @param DateTime $date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="tasks.list", requirements={"id": "\d+", "date": "\d{4}-\d{2}-\d{2}"})
     * @Method("GET")
     */
    public function listAction(Member $member, \DateTime $date)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AirlinesAppBundle:Task')->findByMemberAndDate($member, $date);

        return $this->createJsonResponse($tasks);
    }



    /**
     * Creates a new task for the given Member and date
     * The newly created task will be returned as JSON
     *
     * @param Member   $member
     * @param DateTime $date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="tasks.create", requirements={"id": "\d+", "date": "\d{4}-\d{2}-\d{2}"})
     * @Method("POST")
     */
    public function postAction(Request $request, Member $member, \DateTime $date)
    {
        $manager = $this->get('airlines.task_manager');

        $task = $manager->hydrateFromRequest(new Task(), $request);
        $task->setDate($date);
        $task->setMember($member);

        $errors = $manager->validateAndPersist($task);

        if (0 < count($errors)) {
            return $this->createJsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return $this->createJsonResponse($task, Response::HTTP_CREATED);
    }



    /**
     * Updates a task
     * The updated task will be returned as JSON
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="tasks.update", requirements={"id": "\d+"})
     * @Method("PUT")
     */
    public function putAction(Request $request, Task $task)
    {
        $manager = $this->get('airlines.task_manager');

        $task = $manager->hydrateFromRequest($task, $request);
        $errors = $manager->validateAndPersist($task);

        if (0 < count($errors)) {
            return $this->createJsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        // We use 200 OK instead of 204 No Content for a successful PUT,
        // because the latter prevents any content to be sent (which pretty much makes sense)
        return $this->createJsonResponse($task);
    }



    /**
     * Deletes a task
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="tasks.remove", requirements={"id": "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Task $task)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        return $this->createNoContentResponse();
    }



    /**
     * Splits a task in two
     * The new task will be created for the same member and date as the original one,
     * so we just have to fetch tasks according to these parameters again to update the view
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/split/{id}", name="tasks.split", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function splitAction(Task $task)
    {
        $manager = $this->get('airlines.task_manager');
        $manager->split($task);

        return $this->createNoContentResponse();
    }



    /**
     * Merges a task into another
     * The resulting task will be returned as JSON (the merged one can be removed from the view)
     *
     * @param Task $task   Merged task
     * @param Task $target Target task (in which the other one will be merged)
     *
     * @return Response
     *
     * @Route("/merge/{id}/{target}", name="tasks.merge", requirements={"id": "\d+", "target": "\d+"})
     * @Method("POST")
     */
    public function mergeAction(Task $task, Task $target)
    {
        $manager = $this->get('airlines.task_manager');
        $result = $manager->merge($task, $target);

        if (!$result) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        return $this->createJsonResponse($result);
    }
}
