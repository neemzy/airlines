<?php

namespace Airlines\AppBundle\Controller;

use Airlines\AppBundle\Entity\Board;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/api/member")
 */
class JsonMemberController extends AbstractJsonController
{
    /**
     * @param Board $board
     *
     * @return Response
     *
     * @Route("/{id}", name="member.list", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function listAction(Board $board)
    {
        $members = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('AirlinesAppBundle:Member')
            ->findByBoard($board);

        return $this->createJsonResponse($members);
    }
}
