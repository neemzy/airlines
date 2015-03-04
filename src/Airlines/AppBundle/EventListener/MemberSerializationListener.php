<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\Manager\MemberManager;

class MemberSerializationListener implements EventSubscriberInterface
{
    /**
     * @var MemberManager
     */
    private $manager;

    /**
     * Constructor
     * Binds the Member manager
     *
     * @param MemberManager $manager
     */
    public function __construct(MemberManager $manager)
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
                'class' => 'Airlines\AppBundle\Entity\Member',
                'method' => 'onPostSerialize'
            ]
        ];
    }

    /**
     * Post-serialization callback
     * Binds API URLs to serialized Members
     *
     * @param ObjectEvent $event Subscribed event instance
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $member = $event->getObject();

        $event->getVisitor()->addData('taskUrl', $this->manager->generateRootTaskUrl($member));
    }
}
