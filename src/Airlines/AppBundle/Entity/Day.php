<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Day
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\DayRepository")
 */
class Day
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @ORM\ManyToOne(targetEntity="Week", inversedBy="days")
     * @ORM\JoinColumn(name="week_id", referencedColumnName="id")
     */
    private $week;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="day")
     */
    private $tasks;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Day
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set week
     *
     * @param \Airlines\AppBundle\Entity\Week $week
     * @return Day
     */
    public function setWeek(\Airlines\AppBundle\Entity\Week $week = null)
    {
        $this->week = $week;

        return $this;
    }

    /**
     * Get week
     *
     * @return \Airlines\AppBundle\Entity\Week 
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * Add tasks
     *
     * @param \Airlines\AppBundle\Entity\Task $tasks
     * @return Day
     */
    public function addTask(\Airlines\AppBundle\Entity\Task $tasks)
    {
        $this->tasks[] = $tasks;

        return $this;
    }

    /**
     * Remove tasks
     *
     * @param \Airlines\AppBundle\Entity\Task $tasks
     */
    public function removeTask(\Airlines\AppBundle\Entity\Task $tasks)
    {
        $this->tasks->removeElement($tasks);
    }

    /**
     * Get tasks
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTasks()
    {
        return $this->tasks;
    }

    /**
     * String representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->date->format('d/m/Y');
    }
}
