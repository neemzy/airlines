<?php

namespace Airlines\AppBundle\Controller;

use Airlines\AppBundle\Entity\Member;
use Airlines\AppBundle\Entity\Task;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/task")
 */
class JsonTaskController extends AbstractJsonController
{
    /**
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="task.get", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function getAction(Task $task)
    {
        return $this->createJsonResponse($task);
    }

    /**
     * @param Member $member
     * @param int    $week   Week number
     *
     * @return Response
     *
     * @Route("/{id}/{week}", name="task.week", requirements={"id": "\d+", "week": "\d+"})
     * @Method("GET")
     */
    public function getByWeekNumberAction(Member $member, $week)
    {
        $dates = $this
            ->get('airlines.helper.week_number')
            ->getWorkDaysForWeek($week);

        $tasks = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AirlinesAppBundle:Task')
            ->findByMemberAndDates($member, $dates);

        return $this->createJsonResponse($tasks);
    }

    /**
     * @param Member   $member
     * @param DateTime $date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="task.day", requirements={"id": "\d+", "date": "\d{4}-\d{2}-\d{2}"})
     * @Method("GET")
     */
    public function getByDayAction(Member $member, \DateTime $date)
    {
        $tasks = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AirlinesAppBundle:Task')
            ->findByMemberAndDates($member, [$date->format('Y-m-d')]);

        return $this->createJsonResponse($tasks);
    }

    /**
     * @param Member   $member
     * @param DateTime $date
     *
     * @return Response Newly created Task as JSON
     *
     * @Route("/{id}/{date}", name="task.create", requirements={"id": "\d+", "date": "\d{4}-\d{2}-\d{2}"})
     * @Method("POST")
     */
    public function postAction(Request $request, Member $member, \DateTime $date)
    {
        $manager = $this->get('airlines.manager.task');

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
     * @param Task $task
     *
     * @return Response Updated Task as JSON
     *
     * @Route("/{id}", name="task.update", requirements={"id": "\d+"})
     * @Method("PUT")
     */
    public function putAction(Request $request, Task $task)
    {
        $manager = $this->get('airlines.manager.task');

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
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="task.remove", requirements={"id": "\d+"})
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
     * The new Task will be created for the same member and date as the original one,
     * so we just have to fetch tasks according to these parameters again to update the view
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/split/{id}", name="task.split", requirements={"id": "\d+"})
     * @Method("POST")
     */
    public function splitAction(Task $task)
    {
        $this
            ->get('airlines.manager.task')
            ->split($task);

        return $this->createNoContentResponse();
    }

    /**
     * Merges a Task into another (it can then be safely removed from the view)
     *
     * @param Task $task   Merged Task
     * @param Task $target Target Task (in which the other one will be merged)
     *
     * @return Response Updated Task as JSON
     *
     * @Route("/merge/{id}/{target}", name="task.merge", requirements={"id": "\d+", "target": "\d+"})
     * @Method("POST")
     */
    public function mergeAction(Task $task, Task $target)
    {
        $result = $this
            ->get('airlines.manager.task')
            ->merge($task, $target);

        if (!$result) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        return $this->createJsonResponse($result);
    }
}
