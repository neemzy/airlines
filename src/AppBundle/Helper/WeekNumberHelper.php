<?php

namespace Airlines\AppBundle\Helper;

class WeekNumberHelper
{
    /**
     * @param int $week Week number
     * @param int $year
     *
     * @return array Monda-to-friday DateTime instances
     */
    public function getWorkDaysForWeek($week, $year = null)
    {
        is_null($year) && $year = date('o');
        $dates = [];

        for ($i = 1; $i <= 5; $i++) {
            $dates[] = date('Y-m-d', strtotime($year.'-W'.$week.'-'.$i));
        }

        return $dates;
    }

    /**
     * @param int $week Week number
     * @param int $year
     *
     * @return array Previous/next week numbers and years
     */
    public function getPrevNextWeekYear($week, $year)
    {
        $prevWeek = $week - 1;
        $prevYear = $year;
        $nextWeek = $week + 1;
        $nextYear = $year;

        if ($prevWeek < 1) {
            $prevWeek = 52;
            $prevYear--;
        } else if ($nextWeek > 52) {
            $nextWeek = 1;
            $nextYear++;
        }

        return compact('prevWeek', 'prevYear', 'nextWeek', 'nextYear');
    }
}
