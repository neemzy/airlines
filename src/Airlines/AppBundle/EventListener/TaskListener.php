<?php

namespace Airlines\AppBundle\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Airlines\AppBundle\Entity\Task;
use Airlines\AppBundle\Emitter\TaskEmitter;

class TaskListener
{
    /**
     * @var TaskEmitter
     */
    private $emitter;



    /**
     * Constructor
     * Binds the task emitter
     *
     * @return void
     */
    public function __construct(TaskEmitter $emitter)
    {
        $this->emitter = $emitter;
    }



    /**
     * Emits a Socket.IO event to notify a task creation or update
     *
     * @return void
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function emitEvent(Task $task, LifecycleEventArgs $args)
    {
        $this->emitter->emitEvent($task);
    }
}
