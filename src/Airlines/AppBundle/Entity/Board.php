<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Board
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\BoardRepository")
 */
class Board
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
     * @ORM\OneToMany(targetEntity="Member", mappedBy="board")
     */
    private $members;


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
     * @return Board
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
        $this->members = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add members
     *
     * @param \Airlines\AppBundle\Entity\Member $members
     * @return Board
     */
    public function addMember(\Airlines\AppBundle\Entity\Member $members)
    {
        $this->members[] = $members;

        return $this;
    }

    /**
     * Remove members
     *
     * @param \Airlines\AppBundle\Entity\Member $members
     */
    public function removeMember(\Airlines\AppBundle\Entity\Member $members)
    {
        $this->members->removeElement($members);
    }

    /**
     * Get members
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMembers()
    {
        return $this->members;
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
