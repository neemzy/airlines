<?php

namespace Airlines\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * JSON API generic controller
 *
 * @Route("/api")
 */
class JsonDefaultController extends AbstractJsonController
{
    /**
     * Fetches monday-to-friday dates for the given week number
     *
     * @param int $week
     * @param int $year
     *
     * @return Response
     *
     * @Route("/weekdays/{week}/{year}", name="weekdays", requirements={"week": "\d+"})
     * @Method("GET")
     */
    public function weekDaysAction($week, $year = null)
    {
        $helper = $this->get('airlines.helper.week_number');

        return $this->createJsonResponse($helper->getWorkDaysForWeek($week, $year));
    }
}
