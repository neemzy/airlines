<?php

namespace Airlines\AppBundle\Manager;

use Airlines\AppBundle\Entity\Task;
use Doctrine\Common\Persistence\ObjectManager;

class TaskManager
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
