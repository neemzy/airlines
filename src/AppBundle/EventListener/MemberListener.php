<?php

namespace Airlines\AppBundle\EventListener;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Airlines\AppBundle\Entity\Member;

class MemberListener
{
    /**
     * Prepares avatar file uploading
     * Is it really useful to do it prior to persistance ?
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prepareAvatarUpload(Member $member, LifecycleEventArgs $args)
    {
        $member->prepareAvatarUpload();
    }

    /**
     * Performs avatar file uploading
     *
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadAvatar(Member $member, LifecycleEventArgs $args)
    {
        $member->uploadAvatar();
    }

    /**
     * Deletes avatar file
     *
     * @ORM\PostRemove()
     */
    public function deleteAvatar(Member $member, LifecycleEventArgs $args)
    {
        $member->deleteAvatar();
    }
}
