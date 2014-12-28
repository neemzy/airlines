<?php

namespace Airlines\AppBundle\Helper;

class WeekNumberHelper
{
    /**
     * Fetches monday-to-friday dates for the given week number
     *
     * @param int $week
     * @param int $year
     *
     * @return array DateTime instances
     */
    public function getWorkDaysForWeek($week, $year = null)
    {
        is_null($year) && $year = date('Y');
        $dates = [];

        for ($i = 1; $i <= 5; $i++) {
            $dates[] = date('Y-m-d', strtotime($year.'-W'.$week.'-'.$i));
        }

        return $dates;
    }
}
