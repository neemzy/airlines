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
    private $route;



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
     * @return string
     */
    public function generateApiUrl(Member $member)
    {
        $date = '1970-01-01';

        $url = $this->router->generate(
            'task.list',
            [
                'id' => $member->getId(),
                'date' => $date
            ]
        );

        return str_replace($date, '', $url);
    }
}
