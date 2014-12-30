<?php

namespace Airlines\AppBundle\Tests\Manager;

use Airlines\AppBundle\Entity\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Provides tasks
     * Each task is provided in an array alongside its overconsumption, underestimation and overestimation booleans
     *
     * @return array
     */
    public function taskProvider()
    {
        $task1 = new Task();
        $task1->setEstimate(2);
        $task1->setConsumed(0);
        $task1->setRemaining(3);

        $task2 = new Task();
        $task2->setEstimate(1);
        $task2->setConsumed(1.5);
        $task2->setRemaining(0);

        $task3 = new Task();
        $task3->setEstimate(3);
        $task3->setConsumed(2.5);
        $task3->setRemaining(0);

        $task4 = new Task();
        $task4->setEstimate(1);
        $task4->setConsumed(0.25);
        $task4->setRemaining(0.75);

        return [
            [$task1, false, true, false],
            [$task2, true, true, false],
            [$task3, false, false, true],
            [$task4, false, false, false]
        ];
    }



    /**
     * Checks overconsumption for a task
     *
     * @param Task $task
     * @param bool $overConsumed
     * @param bool $underEstimated
     * @param bool $overEstimated
     *
     * @return void
     *
     * @dataProvider taskProvider
     * @testdox Is considered overconsumed when its consumed time is above its initial estimate
     */
    public function testIsOverConsumed($task, $overConsumed, $underEstimated, $overEstimated)
    {
        $this->assertEquals($overConsumed, $task->isOverConsumed());
    }



    /**
     * Checks underestimation for a task
     *
     * @param Task $task
     * @param bool $overConsumed
     * @param bool $underEstimated
     * @param bool $overEstimated
     *
     * @return void
     *
     * @dataProvider taskProvider
     * @testdox Is considered underestimated when the sum of its consumed and remaining time is above its initial estimate
     */
    public function testWasUnderEstimated($task, $overConsumed, $underEstimated, $overEstimated)
    {
        $this->assertEquals($underEstimated, $task->wasUnderEstimated());
    }




    /**
     * Checks overestimation for a task
     *
     * @param Task $task
     * @param bool $overConsumed
     * @param bool $underEstimated
     * @param bool $overEstimated
     *
     * @return void
     *
     * @dataProvider taskProvider
     * @testdox Is considered overestimated when the sum of its consumed and remaining time is under its initial estimate
     */
    public function testWasOverEstimated($task, $overConsumed, $underEstimated, $overEstimated)
    {
        $this->assertEquals($overEstimated, $task->wasOverEstimated());
    }
}
