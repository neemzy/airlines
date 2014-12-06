<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\MemberRepository")
 */
class Member
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="weeks")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     */
    private $board;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="member")
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
     * Set name
     *
     * @param string $name
     * @return Week
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set board
     *
     * @param \Airlines\AppBundle\Entity\Board $board
     * @return Week
     */
    public function setBoard(\Airlines\AppBundle\Entity\Board $board = null)
    {
        $this->board = $board;

        return $this;
    }

    /**
     * Get board
     *
     * @return \Airlines\AppBundle\Entity\Board 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Add tasks
     *
     * @param \Airlines\AppBundle\Entity\Task $tasks
     * @return Week
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
        return $this->name;
    }
}
