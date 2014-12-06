<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\TaskRepository")
 */
class Task
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
     * @var string
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="estimate", type="decimal")
     */
    private $estimate;

    /**
     * @var string
     *
     * @ORM\Column(name="consumed", type="decimal")
     */
    private $consumed;

    /**
     * @var string
     *
     * @ORM\Column(name="remaining", type="decimal")
     */
    private $remaining;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="tasks")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     */
    private $member;


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
     * @return Task
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
     * Set date
     *
     * @param string $date
     * @return Task
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return string 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set estimate
     *
     * @param string $estimate
     * @return Task
     */
    public function setEstimate($estimate)
    {
        $this->estimate = $estimate;

        return $this;
    }

    /**
     * Get estimate
     *
     * @return string 
     */
    public function getEstimate()
    {
        return $this->estimate;
    }

    /**
     * Set consumed
     *
     * @param string $consumed
     * @return Task
     */
    public function setConsumed($consumed)
    {
        $this->consumed = $consumed;

        return $this;
    }

    /**
     * Get consumed
     *
     * @return string 
     */
    public function getConsumed()
    {
        return $this->consumed;
    }

    /**
     * Set remaining
     *
     * @param string $remaining
     * @return Task
     */
    public function setRemaining($remaining)
    {
        $this->remaining = $remaining;

        return $this;
    }

    /**
     * Get remaining
     *
     * @return string 
     */
    public function getRemaining()
    {
        return $this->remaining;
    }

    /**
     * Set member
     *
     * @param \Airlines\AppBundle\Entity\Member $member
     * @return Task
     */
    public function setMember(\Airlines\AppBundle\Entity\Member $member = null)
    {
        $this->member = $member;

        return $this;
    }

    /**
     * Get member
     *
     * @return \Airlines\AppBundle\Entity\Member 
     */
    public function getMember()
    {
        return $this->member;
    }
}
