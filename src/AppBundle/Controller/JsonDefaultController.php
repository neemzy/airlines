<?php

namespace Airlines\AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

/**
 * @Route("/api")
 */
class JsonDefaultController extends AbstractJsonController
{
    /**
     * @param int $week Week number
     * @param int $year
     *
     * @return Response Monday-to-friday dates
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
