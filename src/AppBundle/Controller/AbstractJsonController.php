<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AbstractJsonController extends Controller
{
    /**
     * @param mixed $data Data to serialize
     * @param int   $code HTTP response code
     *
     * @return Response
     */
    protected function createJsonResponse($data, $code = Response::HTTP_OK)
    {
        $serializer = $this->get('jms_serializer');
        $json = $serializer->serialize($data, 'json');

        return new Response($json, $code, ['content-type' => 'application/json']);
    }

    /**
     * @return Response
     */
    protected function createNoContentResponse()
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
