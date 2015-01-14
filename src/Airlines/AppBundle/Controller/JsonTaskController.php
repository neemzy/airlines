<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Airlines\AppBundle\Entity\Member;
use Airlines\AppBundle\Entity\Task;

/**
 * JSON API Task management controller
 *
 * @Route("/api/task")
 */
class JsonTaskController extends AbstractJsonController
{
    /**
     * Fetches a Task
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="task.get", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function getAction(Task $task)
    {
        $response = $this->createJsonResponse($task);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        $content->restUrl = $manager->generateRestUrl($task);
        $content->splitUrl = $manager->generateSplitUrl($task);
        $content->mergeUrl = $manager->generateMergeUrl($task);
        $response->setContent(json_encode($content));

        return $response;
    }




    /**
     * Fetches all Tasks for the given Member and week number
     *
     * @param Member $member
     * @param int    $week
     *
     * @return Response
     *
     * @Route("/{id}/{week}", name="task.week", requirements={"id": "\d+", "week": "\d+"})
     * @Method("GET")
     */
    public function getByWeekNumberAction(Member $member, $week)
    {
        $helper = $this->get('airlines.week_number_helper');
        $dates = $helper->getWorkDaysForWeek($week);

        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AirlinesAppBundle:Task')->findByMemberAndDates($member, $dates);

        $response = $this->createJsonResponse($tasks);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        foreach ($content as &$task) {
            $instance = $em->getRepository('AirlinesAppBundle:Task')->find($task->id);
            $task->restUrl = $manager->generateRestUrl($instance);
            $task->splitUrl = $manager->generateSplitUrl($instance);
            $task->mergeUrl = $manager->generateMergeUrl($instance);
        }
        $response->setContent(json_encode($content));

        return $response;
    }




    /**
     * Fetches all Tasks for the given Member and date
     *
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
        $em = $this->getDoctrine()->getManager();
        $tasks = $em->getRepository('AirlinesAppBundle:Task')->findByMemberAndDates($member, [$date->format('Y-m-d')]);

        $response = $this->createJsonResponse($tasks);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        foreach ($content as &$task) {
            $instance = $em->getRepository('AirlinesAppBundle:Task')->find($task->id);
            $task->restUrl = $manager->generateRestUrl($instance);
            $task->splitUrl = $manager->generateSplitUrl($instance);
            $task->mergeUrl = $manager->generateMergeUrl($instance);
        }
        $response->setContent(json_encode($content));

        return $response;
    }



    /**
     * Creates a new Task for the given Member and date
     * The newly created Task will be returned as JSON
     *
     * @param Member   $member
     * @param DateTime $date
     *
     * @return Response
     *
     * @Route("/{id}/{date}", name="task.create", requirements={"id": "\d+", "date": "\d{4}-\d{2}-\d{2}"})
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

        $response = $this->createJsonResponse($task, Response::HTTP_CREATED);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        $content->restUrl = $manager->generateRestUrl($task);
        $content->splitUrl = $manager->generateSplitUrl($task);
        $content->mergeUrl = $manager->generateMergeUrl($task);
        $response->setContent(json_encode($content));

        return $response;
    }



    /**
     * Updates a Task
     * The updated Task will be returned as JSON
     *
     * @param Task $task
     *
     * @return Response
     *
     * @Route("/{id}", name="task.update", requirements={"id": "\d+"})
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
        $response = $this->createJsonResponse($task);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        $content->restUrl = $manager->generateRestUrl($task);
        $content->splitUrl = $manager->generateSplitUrl($task);
        $content->mergeUrl = $manager->generateMergeUrl($task);
        $response->setContent(json_encode($content));

        return $response;
    }



    /**
     * Deletes a Task
     *
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
     * Splits a Task in two
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
        $manager = $this->get('airlines.task_manager');
        $manager->split($task);

        return $this->createNoContentResponse();
    }



    /**
     * Merges a Task into another
     * The resulting Task will be returned as JSON (the merged one can be removed from the view)
     *
     * @param Task $task   Merged Task
     * @param Task $target Target Task (in which the other one will be merged)
     *
     * @return Response
     *
     * @Route("/merge/{id}/{target}", name="task.merge", requirements={"id": "\d+", "target": "\d+"})
     * @Method("POST")
     */
    public function mergeAction(Task $task, Task $target)
    {
        $manager = $this->get('airlines.task_manager');
        $result = $manager->merge($task, $target);

        if (!$result) {
            return new Response(null, Response::HTTP_BAD_REQUEST);
        }

        $response = $this->createJsonResponse($result);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.task_manager');
        $content = json_decode($response->getContent());
        $content->restUrl = $manager->generateRestUrl($result);
        $content->splitUrl = $manager->generateSplitUrl($result);
        $content->mergeUrl = $manager->generateMergeUrl($result);
        $response->setContent(json_encode($content));

        return $response;
    }
}
