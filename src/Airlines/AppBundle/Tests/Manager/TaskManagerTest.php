<?php

namespace Airlines\AppBundle\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Airlines\AppBundle\Entity\Task;
use Airlines\AppBundle\Manager\TaskManager;

class TaskManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Crafts a task manager with mocked dependencies
     *
     * @return TaskManager
     */
    private function getTaskManager()
    {
        $manager = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
                        ->getMock();

        $validator = $this->getMockBuilder('Symfony\Component\Validator\Validator')
                          ->disableOriginalConstructor()
                          ->getMock();

        return new TaskManager($manager, $validator);
    }



    /**
     * Crafts a task collection
     *
     * @return ArrayCollection
     */
    public function getTaskCollection()
    {
        $task1 = new Task();
        $task1->setEstimate(1.125);
        $task1->setConsumed(0.125);
        $task1->setRemaining(1);

        $task2 = new Task();
        $task2->setEstimate(2.125);
        $task2->setConsumed(0.625);
        $task2->setRemaining(1.5);

        $task3 = new Task();
        $task3->setEstimate(3);
        $task3->setConsumed(0);
        $task3->setRemaining(3);

        return new ArrayCollection([$task1, $task2, $task3]);
    }



    /**
     * Checks total estimate calculation for a task collection
     *
     * @return void
     *
     * @testdox Can fetch the total estimated time for a task collection
     */
    public function testGetTotalEstimate()
    {
        $manager = $this->getTaskManager();
        $collection = $this->getTaskCollection();

        $this->assertEquals(6.25, $manager->getTotalEstimate($collection));
    }



    /**
     * Checks total consumed calculation for a task collection
     *
     * @return void
     *
     * @testdox Can fetch the total consumed time for a task collection
     */
    public function testGetTotalConsumed()
    {
        $manager = $this->getTaskManager();
        $collection = $this->getTaskCollection();

        $this->assertEquals(0.75, $manager->getTotalConsumed($collection));
    }



    /**
     * Checks total remaining calculation for a task collection
     *
     * @return void
     *
     * @testdox Can fetch the total remaining time for a task collection
     */
    public function testGetTotalRemaining()
    {
        $manager = $this->getTaskManager();
        $collection = $this->getTaskCollection();

        $this->assertEquals(5.5, $manager->getTotalRemaining($collection));
    }
}
