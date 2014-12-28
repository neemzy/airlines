<?php

namespace Airlines\AppBundle\Tests\Helper;

use Airlines\AppBundle\Helper\WeekNumberHelper;

class WeekNumberHelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
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
}
