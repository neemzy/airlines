<?php

namespace Airlines\AppBundle\Tests\Manager;

use Airlines\AppBundle\Entity\Task;

class TaskTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Crafts tasks
     *
     * @return array
     */
    private function getTasks()
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

        return [$task1, $task2, $task3, $task4];
    }



    /**
     * Checks overconsumption for a task
     *
     * @return void
     */
    public function testIsOverConsumed()
    {
        $tasks = $this->getTasks();

        $this->assertFalse($tasks[0]->isOverConsumed());
        $this->assertTrue($tasks[1]->isOverConsumed());
        $this->assertFalse($tasks[2]->isOverConsumed());
        $this->assertFalse($tasks[3]->isOverConsumed());
    }



    /**
     * Checks underestimation for a task
     *
     * @return void
     */
    public function testWasUnderEstimated()
    {
        $tasks = $this->getTasks();

        $this->assertTrue($tasks[0]->wasUnderEstimated());
        $this->assertTrue($tasks[1]->wasUnderEstimated());
        $this->assertFalse($tasks[2]->wasUnderEstimated());
        $this->assertFalse($tasks[3]->wasUnderEstimated());
    }




    /**
     * Checks overestimation for a task
     *
     * @return void
     */
    public function testWasOverEstimated()
    {
        $tasks = $this->getTasks();

        $this->assertFalse($tasks[0]->wasOverEstimated());
        $this->assertFalse($tasks[1]->wasOverEstimated());
        $this->assertTrue($tasks[2]->wasOverEstimated());
        $this->assertFalse($tasks[3]->wasOverEstimated());
    }
}
