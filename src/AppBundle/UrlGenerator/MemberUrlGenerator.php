<?php

namespace Airlines\AppBundle\UrlGenerator;

use Airlines\AppBundle\Entity\Member;
use Symfony\Component\Routing\RouterInterface;

class MemberUrlGenerator
{
    /** @var RouterInterface */
    private $router;

    /**
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
