<?php

namespace Airlines\AppBundle\Manager;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Validator\Validator\LegacyValidator as Validator;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Collection;
use Airlines\AppBundle\Entity\Task;

class TaskManager
{
    /**
     * @var ObjectManager
     */
    private $manager;

    /**
     * @var Validator
     */
    private $validator;

    /**
     * @var RouterInterface
     */
    private $router;



    /**
     * Constructor
     * Binds dependencies
     *
     * @param ObjectManager   $manager
     * @param Validator       $validator
     * @param RouterInterface $router
     *
     * @return void
     */
    public function __construct(ObjectManager $manager, Validator $validator, RouterInterface $router)
    {
        $this->manager = $manager;
        $this->validator = $validator;
        $this->router = $router;
    }



    /**
     * Hydrates a task from a request's data
     *
     * @param Task    $task
     * @param Request $request
     *
     * @return Task Hydrated instance
     */
    public function hydrateFromRequest(Task $task, Request $request)
    {
        if ($request->request->has('name')) {
            $task->setName($request->get('name'));
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
            $member = $this->manager->getRepository('AirlinesAppBundle:Task')->find($id);
            $task->setMember($member);
        }

        return $task;
    }



    /**
     * Fool-proofs a task and persists it if it is valid
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
     * Splits a task in two
     *
     * @param Task $task
     *
     * @return void
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
    }



    /**
     * Merges a task into another
     *
     * @return Task|bool Resulting task or false in case of failure
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



    /**
     * Calculates the total estimate for a task collection
     *
     * @param Collection $collection Task collection
     *
     * @return float
     */
    public function getTotalEstimate(Collection $collection)
    {
        $total = 0;

        foreach ($collection as $task) {
            $total += $task->getEstimate();
        }

        return $total;
    }



    /**
     * Calculates the total consumed for a task collection
     *
     * @param Collection $collection Task collection
     *
     * @return float
     */
    public function getTotalConsumed(Collection $collection)
    {
        $total = 0;

        foreach ($collection as $task) {
            $total += $task->getConsumed();
        }

        return $total;
    }



    /**
     * Calculates the total remaining for a task collection
     *
     * @param Collection $collection Task collection
     *
     * @return float
     */
    public function getTotalRemaining(Collection $collection)
    {
        $total = 0;

        foreach ($collection as $task) {
            $total += $task->getRemaining();
        }

        return $total;
    }



    /**
     * Generates REST API URL for a Task
     *
     * @param Task $task
     *
     * @return string
     */
    public function generateRestUrl(Task $task)
    {
        return $this->router->generate('task.get', ['id' => $task->getId()]);
    }



    /**
     * Generates split API URL for a Task
     *
     * @param Task $task
     *
     * @return string
     */
    public function generateSplitUrl(Task $task)
    {
        return $this->router->generate('task.split', ['id' => $task->getId()]);
    }
}
