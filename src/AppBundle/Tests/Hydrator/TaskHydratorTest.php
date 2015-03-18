<?php

namespace Airlines\AppBundle\Tests\Hydrator;

use Airlines\AppBundle\Entity\Member;
use Airlines\AppBundle\Entity\Task;
use Airlines\AppBundle\Hydrator\TaskHydrator;

class TaskHydratorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @testdox Can hydrate a task from a request
     */
    public function testHydrateFromRequest()
    {
        $memberName = 'John Doe';
        $member = new Member();
        $member->setName($memberName);

        $repository = $this
            ->getMockBuilder('Airlines\AppBundle\Entity\MemberRepository')
            ->disableOriginalConstructor()
            ->setMethods(['find'])
            ->getMock();

        $repository
            ->expects($this->once())
            ->method('find')
            ->will($this->returnValue($member));

        $em = $this
            ->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->setMethods(['getRepository'])
            ->getMock();

        $em
            ->expects($this->once())
            ->method('getRepository')
            ->with('AirlinesAppBundle:Member')
            ->will($this->returnValue($repository));

        $hydrator = new TaskHydrator($em);

        $filebag = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\FileBag')
            ->setMethods(['has', 'get'])
            ->getMock();

        $filebag
            ->expects($this->any())
            ->method('has')
            ->will($this->returnValue(true));

        $request = $this
            ->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->getMock();

        $request->request = $filebag;

        $name = 'Some task name';
        $date = '2015-01-07'; // Je suis Charlie
        $estimate = 2;
        $consumed = 1;
        $remaining = 1.5;

        $request
            ->expects($this->any())
            ->method('get')
            ->will(
                $this->returnValueMap(
                    [
                        ['name', null, false, $name],
                        ['date', null, false, $date],
                        ['estimate', null, false, $estimate],
                        ['consumed', null, false, $consumed],
                        ['remaining', null, false, $remaining],
                        ['member', null, false, null]
                    ]
                )
            );

        $task = $hydrator->hydrateFromRequest(new Task(), $request);

        $this->assertEquals($name, $task->getName());
        $this->assertEquals($date, $task->getDate()->format('Y-m-d'));
        $this->assertEquals($estimate, $task->getEstimate());
        $this->assertEquals($consumed, $task->getConsumed());
        $this->assertEquals($remaining, $task->getRemaining());
        $this->assertEquals($memberName, $task->getMember()->getName());
    }
}
