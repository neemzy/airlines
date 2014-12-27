<?php

namespace Airlines\AppBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Abstract JSON API controller
 */
class AbstractJsonController extends Controller
{
    /**
     * Returns a JSON response
     *
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
     * Returns a "No Content" (204) response
     *
     * @return Response
     */
    protected function createNoContentResponse()
    {
        return new Response(null, Response::HTTP_NO_CONTENT);
    }
}
