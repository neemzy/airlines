<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\UrlGenerator\TaskUrlGenerator;

class TaskSerializationListener implements EventSubscriberInterface
{
    /** @var TaskUrlGenerator */
    private $UrlGenerator;

    /**
     * @param TaskUrlGenerator $UrlGenerator
     */
    public function __construct(TaskUrlGenerator $UrlGenerator)
    {
        $this->UrlGenerator = $UrlGenerator;
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

        $event->getVisitor()->addData('restUrl', $this->UrlGenerator->generateRestUrl($task));
        $event->getVisitor()->addData('splitUrl', $this->UrlGenerator->generateSplitUrl($task));
        $event->getVisitor()->addData('mergeUrl', $this->UrlGenerator->generateMergeUrl($task));
    }
}
