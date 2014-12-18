<?php

namespace Airlines\AppBundle\EventListener;

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
     * Task post-persist event callback
     *
     * @return void
     */
    public function postPersist(Task $task, LifecycleEventArgs $args)
    {
        $this->emitter->emitEvent($task);
    }



    /**
     * Task post-update event callback
     *
     * @return void
     */
    public function postUpdate(Task $task, LifecycleEventArgs $args)
    {
        $this->emitter->emitEvent($task);
    }
}
