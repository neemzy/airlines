<?php

namespace Airlines\AppBundle\Manager;

use Airlines\AppBundle\Entity\Task;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ValidatorInterface;

class TaskManager
{
    /** @var ObjectManager */
    private $manager;

    /** @var ValidatorInterface */
    private $validator;

    /**
     * Constructor
     * Binds dependencies
     *
     * @param ObjectManager      $manager
     * @param ValidatorInterface $validator
     */
    public function __construct(ObjectManager $manager, ValidatorInterface $validator)
    {
        $this->manager = $manager;
        $this->validator = $validator;
    }

    /**
     * Hydrates a Task from a request's data
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return Task Hydrated instance
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

    /**
     * Fool-proofs a Task and persists it if it is valid
     *
     * @param Task $task
     *
     * @return array Error messages
     */
    public function validateAndPersist(Task $task)
    {
        $errors = $this->validator->validate($task);

        if (0 == count($errors)) {
            $this->manager->persist($task);
            $this->manager->flush();
        }

        return $errors;
    }

    /**
     * Splits a Task in two
     *
     * @param Task $task
     *
     * @return Task Newly created Task
     */
    public function split(Task $task)
    {
        $estimate = $task->getEstimate() / 2;
        $consumed = $task->getConsumed() / 2;
        $remaining = $task->getRemaining() / 2;

        $split = new Task();
        $split->setName($task->getName());
        $split->setDate($task->getDate());
        $split->setEstimate($estimate);
        $split->setConsumed($consumed);
        $split->setRemaining($remaining);
        $split->setMember($task->getMember());

        $task->setEstimate($estimate);
        $task->setConsumed($consumed);
        $task->setRemaining($remaining);

        $this->manager->persist($task);
        $this->manager->persist($split);
        $this->manager->flush();

        return $split;
    }

    /**
     * Merges a Task into another
     *
     * @param Task $task   Task to merge (will be removed)
     * @param Task $target Merge target (will be updated)
     *
     * @return Task|bool Resulting Task or false in case of failure
     */
    public function merge(Task $task, Task $target)
    {
        if ($task->getId() == $target->getId()) {
            return false;
        }

        $target->setEstimate($task->getEstimate() + $target->getEstimate());
        $target->setConsumed($task->getConsumed() + $target->getConsumed());
        $target->setRemaining($task->getRemaining() + $target->getRemaining());

        $this->manager->persist($target);
        $this->manager->remove($task);
        $this->manager->flush();

        return $target;
    }
}
