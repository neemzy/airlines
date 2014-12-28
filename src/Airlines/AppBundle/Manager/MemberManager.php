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
     *
     * @return void
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }



    /**
     * Generates root API URL for a Member
     *
     * @param Member $member
     *
     * @return string
     */
    public function generateApiUrl(Member $member)
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
