<?php

namespace Airlines\AppBundle\EventListener;

use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use Airlines\AppBundle\UrlGenerator\MemberUrlGenerator;

class MemberSerializationListener implements EventSubscriberInterface
{
    /** @var MemberUrlGenerator */
    private $urlGenerator;

    /**
     * @param MemberUrlGenerator $urlGenerator
     */
    public function __construct(MemberUrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
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

        $event->getVisitor()->addData('taskUrl', $this->urlGenerator->generateRootTaskUrl($member));
    }
}
