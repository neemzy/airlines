<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\Manager\MemberManager;

class MemberSerializationListener implements EventSubscriberInterface
{
    /** @var MemberManager */
    private $manager;

    /**
     * @param MemberManager $manager
     */
    public function __construct(MemberManager $manager)
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
                'class' => 'Airlines\AppBundle\Entity\Member',
                'method' => 'onPostSerialize'
            ]
        ];
    }

    /**
     * Binds API URLs to serialized Members
     *
     * @param ObjectEvent
     */
    public function onPostSerialize(ObjectEvent $event)
    {
        $member = $event->getObject();

        $event->getVisitor()->addData('taskUrl', $this->manager->generateRootTaskUrl($member));
    }
}
