<?php

namespace Airlines\AppBundle\Tests\Helper;

use Airlines\AppBundle\Helper\WeekNumberHelper;

class WeekNumberHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides week/year couples
     * Values are provided in an array alongside expected previous and next values for both
     *
     * @return array
     */
    public function weekYearProvider()
    {
        return [
            // week, year, previous week number, previous week's year, next week number, next week's year
            [40, 2014, 39, 2014, 41, 2014],
            [52, 2014, 51, 2014, 1, 2015],
            [1, 2015, 52, 2014, 2, 2015]
        ];
    }

    /**
     * Checks working days dates per week number calculation
     *
     * @testdox Can fetch working days dates for given week and year
     */
    public function testGetWorkDaysForWeek()
    {
        $helper = new WeekNumberHelper();
        $days = $helper->getWorkDaysForWeek(52, 2014);

        $this->assertInternalType('array', $days);
        $this->assertCount(5, $days);
        $this->assertEquals('2014-12-22', $days[0]);
        $this->assertEquals('2014-12-23', $days[1]);
        $this->assertEquals('2014-12-24', $days[2]);
        $this->assertEquals('2014-12-25', $days[3]);
        $this->assertEquals('2014-12-26', $days[4]);
    }

    /**
     * Checks previous and next week/year couples calculation
     *
     * @param int $week
     * @param int $year
     * @param int $prevWeek Expected previous week number
     * @param int $prevYear Expected previous week's year
     * @param int $nextWeek Expected next week number
     * @param int $nextYear Expected next week's year
     *
     * @dataProvider weekYearProvider
     * @testdox Can fetch previous and next week/year couples for given week and year
     */
    public function testGetPrevNextWeekYear($week, $year, $prevWeek, $prevYear, $nextWeek, $nextYear)
    {
        $helper = new WeekNumberHelper();
        $couples = $helper->getPrevNextWeekYear($week, $year);
        $keys = ['prevWeek', 'prevYear', 'nextWeek', 'nextYear'];

        $this->assertInternalType('array', $couples);
        $this->assertCount(count($keys), $couples);

        foreach ($keys as $key) {
            $this->assertArrayHasKey($key, $couples);
            $this->assertEquals($$key, $couples[$key]);
        }
    }
}
