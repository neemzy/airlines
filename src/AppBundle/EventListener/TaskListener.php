<?php

namespace Airlines\AppBundle\EventListener;

use Airlines\AppBundle\Emitter\TaskEmitter;
use Airlines\AppBundle\Entity\Task;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;

class TaskListener
{
    /** @var TaskEmitter */
    private $emitter;

    /**
     * @param TaskEmitter $emitter
     */
    public function __construct(TaskEmitter $emitter)
    {
        $this->emitter = $emitter;
    }

    /**
     * Emits a Socket.IO event to notify a Task creation or update
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function emitEvent(Task $task, LifecycleEventArgs $args)
    {
        $this->emitter->emitEvent($task);
    }
}
