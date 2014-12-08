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
    private function toJSON($data, $code = Response::HTTP_OK)
    {
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($data, 'json');

        return new Response($json, $code, ['content-type' => 'application/json']);
    }



    /**
     * Retrieves all tasks for the given Member and date
     *
     * @param Member $member Member instance
     * @param string $date   SQL-formatted date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="getTasks")
     * @ParamConverter("member", class="AirlinesAppBundle:Member")
     * @Method("GET")
     */
    public function getAction(Member $member, $date)
    {
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AirlinesAppBundle:Task')->findByMemberAndDate($member, $date);

        return $this->toJSON($tasks);
    }



    /**
     * Creates a new task for the given Member and date
     *
     * @param Member $member Member instance
     * @param string $date   SQL-formatted date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="createTask")
     * @ParamConverter("member", class="AirlinesAppBundle:Member")
     * @Method("POST")
     */
    public function postAction(Request $request, Member $member, $date)
    {
        // This is ugly, how can I do this differently ?
        $task = new Task();
        $task->setName($request->get('name'));
        $task->setDate(new \DateTime($date));
        $task->setEstimate($request->get('estimate'));
        $task->setConsumed($request->get('consumed'));
        $task->setRemaining($request->get('remaining'));
        $task->setMember($member);

        $validator = $this->get('validator');
        $errors = $validator->validate($task);

        if (0 < count($errors)) {
            return $this->toJSON($errors, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($task);
        $em->flush();

        return $this->toJSON($task, Response::HTTP_CREATED);
    }



    /**
     * Updates the task at the given id
     *
     * @param int $id Task id
     *
     * @return Response
     *
     * @Route("/{id}", name="updateTask")
     * @Method("PUT")
     */
    public function putAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AirlinesAppBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException();
        }

        // This is ugly, how can I do this differently ?
        $request->request->has('name')      && $task->setName($request->get('name'));
        $request->request->has('estimate')  && $task->setEstimate($request->get('estimate'));
        $request->request->has('consumed')  && $task->setConsumed($request->get('consumed'));
        $request->request->has('remaining') && $task->setRemaining($request->get('remaining'));

        $validator = $this->get('validator');
        $errors = $validator->validate($task);

        if (0 < count($errors)) {
            return $this->toJSON($errors, Response::HTTP_BAD_REQUEST);
        }

        $em->persist($task);
        $em->flush();

        // We use 200 OK instead of 204 No Content for a successful PUT,
        // because the latter prevents any content to be sent (which pretty much makes sense)
        return $this->toJSON($task);
    }



    /**
     * Deletes the task at the given id
     *
     * @param int $id Task id
     *
     * @return Response
     *
     * @Route("/{id}", name="deleteTask")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository('AirlinesAppBundle:Task')->find($id);

        if (!$task) {
            throw $this->createNotFoundException();
        }

        $em->remove($task);
        $em->flush();

        return $this->toJSON($task, Response::HTTP_NO_CONTENT);
    }
}
