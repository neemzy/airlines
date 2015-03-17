<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\Manager\TaskManager;

class TaskSerializationListener implements EventSubscriberInterface
{
    /** @var TaskManager */
    private $manager;

    /**
     * @param TaskManager $manager
     */
    public function __construct(TaskManager $manager)
    {
        $this->manager = $manager;
    }

    /**
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
     * Binds API URLs to serialized Tasks
     *
     * @param ObjectEvent $event
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $task = $event->getObject();

        $event->getVisitor()->addData('restUrl', $this->manager->generateRestUrl($task));
        $event->getVisitor()->addData('splitUrl', $this->manager->generateSplitUrl($task));
        $event->getVisitor()->addData('mergeUrl', $this->manager->generateMergeUrl($task));
    }
}
