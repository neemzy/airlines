<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Week
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\WeekRepository")
 */
class Week
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
     * @ORM\OneToMany(targetEntity="Day", mappedBy="week")
     */
    private $days;


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
        $this->days = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add days
     *
     * @param \Airlines\AppBundle\Entity\Day $days
     * @return Week
     */
    public function addDay(\Airlines\AppBundle\Entity\Day $days)
    {
        $this->days[] = $days;

        return $this;
    }

    /**
     * Remove days
     *
     * @param \Airlines\AppBundle\Entity\Day $days
     */
    public function removeDay(\Airlines\AppBundle\Entity\Day $days)
    {
        $this->days->removeElement($days);
    }

    /**
     * Get days
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDays()
    {
        return $this->days;
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
