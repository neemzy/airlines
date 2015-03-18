<?php

namespace Airlines\AppBundle\Entity;

use JMS\Serializer\Annotation as Serializer;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Task
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\TaskRepository")
 * @ORM\EntityListeners({"Airlines\AppBundle\EventListener\TaskListener"})
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
     * @ORM\Column(name="name", type="text", length=255)
     * @Assert\NotBlank
     */
    private $name; // TODO: find a better way to display a textarea in the form than altering the database column type

    /**
     * @var string
     *
     * @ORM\Column(name="date", type="date")
     * @Assert\NotBlank
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="estimate", type="decimal", precision=5, scale=3)
     * @Assert\Type("numeric")
     */
    private $estimate;

    /**
     * @var string
     *
     * @ORM\Column(name="consumed", type="decimal", precision=5, scale=3)
     * @Assert\Type("numeric")
     */
    private $consumed;

    /**
     * @var string
     *
     * @ORM\Column(name="remaining", type="decimal", precision=5, scale=3)
     * @Assert\Type("numeric")
     */
    private $remaining;

    /**
     * @ORM\ManyToOne(targetEntity="Member", inversedBy="tasks")
     * @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     * @Assert\NotBlank
     * @Serializer\Exclude
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



    /**
     * Retrieves owner Member's id
     * Used for serialization to JSON
     *
     * @return bool
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("member")
     */
    public function getMemberId()
    {
        return $this->member->getId();
    }
}
