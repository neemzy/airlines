<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\Manager\TaskManager;

class TaskSerializationListener implements EventSubscriberInterface
{
    /**
     * @var TaskManager
     */
    private $manager;



    /**
     * Constructor
     * Binds the Task manager
     *
     * @param TaskManager $manager
     *
     * @return void
     */
    public function __construct(TaskManager $manager)
    {
        $this->manager = $manager;
    }



    /**
     * Defines serialization events this listener is subscribed to
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            [
                'event' => 'serializer.post_serialize',
                'class' => 'Airlines\AppBundle\Entity\Task',
                'method' => 'onPostSerialize'
            ]
        ];
    }



    /**
     * Post-serialization callback
     * Binds API URLs to serialized Tasks
     *
     * @param ObjectEvent $event Subscribed event instance
     *
     * @return void
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $task = $event->getObject();

        $event->getVisitor()->addData('restUrl', $this->manager->generateRestUrl($task));
        $event->getVisitor()->addData('splitUrl', $this->manager->generateSplitUrl($task));
        $event->getVisitor()->addData('mergeUrl', $this->manager->generateMergeUrl($task));
    }
}
