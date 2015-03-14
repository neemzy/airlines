<?php

namespace Airlines\AppBundle\Manager;

use Symfony\Component\Routing\RouterInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Validator;
use Symfony\Component\HttpFoundation\Request;
use Airlines\AppBundle\Entity\Member;

class MemberManager
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * Constructor
     * Binds dependencies
     *
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * Generates root Task API URL for a Member
     *
     * @param Member $member
     *
     * @return string
     */
    public function generateRootTaskUrl(Member $member)
    {
        $week = '00';

        $url = $this->router->generate(
            'task.week',
            [
                'id' => $member->getId(),
                'week' => $week
            ]
        );

        return str_replace($week, '', $url);
    }
}
