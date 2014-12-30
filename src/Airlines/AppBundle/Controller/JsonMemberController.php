<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Airlines\AppBundle\Entity\Board;

/**
 * JSON API Member management controller
 *
 * @Route("/api/member")
 */
class JsonMemberController extends AbstractJsonController
{
    /**
     * Fetches all Members for the given Board
     *
     * @param Board $board
     *
     * @return Response
     *
     * @Route("/{id}", name="member.list", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function listAction(Board $board)
    {
        $em = $this->getDoctrine()->getManager();
        $members = $em->getRepository('AirlinesAppBundle:Member')->findByBoard($board);

        $response = $this->createJsonResponse($members);

        // FIXME : this ugly solution is here because I have *no* fucking idea
        // how to add a custom property to the JSON sput out by JMS's Serializer.
        // Defining a handler allows you to send whatever the fuck you want *instead*,
        // but *no* hints about how to keep everything working like default
        // and just add a goddamn fucking field to it. You're welcome.
        $manager = $this->get('airlines.member_manager');
        $content = json_decode($response->getContent());
        foreach ($content as &$member) {
            $member->taskUrl = $manager->generateRootTaskUrl($em->getRepository('AirlinesAppBundle:Member')->find($member->id));
        }
        $response->setContent(json_encode($content));

        return $response;
    }
}
