<?php

namespace Airlines\AppBundle\Emitter;

use SocketIO\Emitter;
use Airlines\AppBundle\Entity\Task;

class TaskEmitter
{
    /**
     * @var Emitter
     */
    private $emitter;



    /**
     * Constructor
     * Binds socket emitter
     *
     * @param Emitter $emitter
     *
     * @return void
     */
    public function __construct(Emitter $emitter)
    {
        $this->emitter = $emitter;
    }



    /**
     * Emits a Socket.IO event upon persisting a task to trigger realtime updates on connected clients
     *
     * @return void
     */
    public function emitEvent(Task $task)
    {
        $this->emitter->emit('task', [ 'id' => $task->getId() ]);
    }
}
