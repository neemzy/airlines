<?php

namespace Airlines\AppBundle\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Airlines\AppBundle\Entity\Member;

class MemberListener
{
    /**
     * Is it really useful to do this prior to persistance ?
     *
     * @param Member             $member
     * @param LifecycleEventArgs $args
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prepareAvatarUpload(Member $member, LifecycleEventArgs $args)
    {
        $member->prepareAvatarUpload();
    }

    /**
     * @param Member             $member
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadAvatar(Member $member, LifecycleEventArgs $args)
    {
        $member->uploadAvatar();
    }

    /**
     * @param Member             $member
     * @param LifecycleEventArgs $args
     *
     * @ORM\PostRemove()
     */
    public function deleteAvatar(Member $member, LifecycleEventArgs $args)
    {
        $member->deleteAvatar();
    }
}
