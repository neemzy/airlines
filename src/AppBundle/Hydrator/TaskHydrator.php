<?php

namespace Airlines\AppBundle\Hydrator;

use Airlines\AppBundle\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

class TaskHydrator
{
    /** @var ObjectManager */
    private $manager;

    /**
     * @param ObjectManager $manager
     */
    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Hydrates a Task from a request's data
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return Task
     */
    public function hydrateFromRequest(Task $task, Request $request)
    {
        if ($request->request->has('name')) {
            // Strip HTML out, but preserve newlines
            $name = $request->get('name');
            $name = preg_replace('/<br([\s\/]*)>/', PHP_EOL, $name);
            $name = strip_tags($name);

            $task->setName($name);
        }

        if ($request->request->has('date')) {
            $date = new \DateTime($request->get('date'));
            $task->setDate($date);
        }

        if ($request->request->has('estimate')) {
            $task->setEstimate($request->get('estimate'));
        }

        if ($request->request->has('consumed')) {
            $task->setConsumed($request->get('consumed'));
        }

        if ($request->request->has('remaining')) {
            $task->setRemaining($request->get('remaining'));
        }

        if ($request->request->has('member')) {
            $member = $this->manager->getRepository('AirlinesAppBundle:Member')->find($request->get('member'));
            $task->setMember($member);
        }

        return $task;
    }
}
