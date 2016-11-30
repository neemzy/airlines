<?php

namespace Airlines\AppBundle\Emitter;

use Airlines\AppBundle\Entity\Task;
use SocketIO\Emitter;

class TaskEmitter
{
    /** @var Emitter */
    private $emitter;

    /**
     * @param Emitter $emitter
     */
    public function __construct(Emitter $emitter = null)
    {
        $this->emitter = $emitter;
    }

    /**
     * Emits a Socket.IO event upon persisting a task to trigger realtime updates on connected clients
     */
    public function emitEvent(Task $task)
    {
        if (is_null($this->emitter)) {
            return;
        }

        $this->emitter->emit('task', ['id' => $task->getId()]);
    }
}
