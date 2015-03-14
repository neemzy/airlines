<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Airlines\AppBundle\Entity\Board;

/**
 * JSON API Board management controller
 *
 * @Route("/api/board")
 */
class JsonBoardController extends AbstractJsonController
{
    /**
     * Updates a Board
     * We only take the "name" parameter into account
     *
     * @param Board $board
     *
     * @return Response
     *
     * @Route("/{id}", name="board.update", requirements={"id": "\d+"})
     * @Method("PUT")
     */
    public function putAction(Request $request, Board $board)
    {
        if ($request->request->has('name')) {
            $board->setName(strip_tags($request->get('name')));
        }

        $errors = $this->get('validator')->validate($board);

        if (0 < count($errors)) {
            return $this->createJsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($board);
        $em->flush();

        return $this->createNoContentResponse();
    }
}
