<?php

namespace Airlines\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Member
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Airlines\AppBundle\Entity\MemberRepository")
 * @ORM\EntityListeners({"Airlines\AppBundle\EventListener\MemberListener"})
 */
class Member
{
    const DEFAULT_AVATAR_PATH = '../../default-avatar.jpg';
    const WEB_UPLOAD_DIR = 'uploads/avatars';

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
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="color", type="string", length=7)
     * @Assert\NotBlank
     * @Assert\Regex("/^#[0-9A-F]{6}$/")
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=255, nullable=true)
     * @Serializer\Exclude
     */
    private $avatar;

    /**
     * @var string
     *
     * @see http://symfony.com/doc/current/cookbook/doctrine/file_uploads.html
     *
     * @Serializer\Exclude
     */
    private $avatarTemp;

    /**
     * @var UploadedFile
     *
     * @Assert\Image
     * @Serializer\Exclude
     */
    private $avatarFile;

    /**
     * @ORM\ManyToOne(targetEntity="Board", inversedBy="members")
     * @ORM\JoinColumn(name="board_id", referencedColumnName="id")
     * @Serializer\Exclude
     */
    private $board;

    /**
     * @ORM\OneToMany(targetEntity="Task", mappedBy="member")
     * @Serializer\Exclude
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
     * @return Member
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
     * Set color
     *
     * @param string $color
     * @return Member
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
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
     * @return Member
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
     * @return Member
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



    /**
     * Gets avatar absolute path
     *
     * @return string
     */
    public function getAvatarAbsolutePath()
    {
        return null === $this->avatar
            ? null
            : $this->getAvatarAbsoluteDir().'/'.$this->avatar;
    }



    /**
     * Gets avatar web path
     * Drops in a default picture if needed
     *
     * @return string
     *
     * @Serializer\VirtualProperty
     * @Serializer\SerializedName("avatar")
     */
    public function getAvatarWebPath()
    {
        $avatar = (null === $this->avatar) ? self::DEFAULT_AVATAR_PATH : $this->avatar;

        return $this->getAvatarWebDir().'/'.$avatar;
    }



    /**
     * Sets avatar file
     *
     * @param UploadedFile $file
     *
     * @return Member
     */
    public function setAvatarFile(UploadedFile $file = null)
    {
        $this->avatarFile = $file;

        if (isset($this->avatar)) {
            $this->avatarTemp = $this->avatar;
            $this->avatar = null;
        } else {
            $this->avatar = 'initial'; // why ?
        }

        return $this;
    }



    /**
     * Gets avatar file
     *
     * @return UploadedFile
     */
    public function getAvatarFile()
    {
        return $this->avatarFile;
    }



    /**
     * Prepares avatar file uploading
     */
    public function prepareAvatarUpload()
    {
        if (null !== $this->avatarFile) {
            $this->avatar = $this->generateAvatarName($this->avatarFile->guessExtension());
        }
    }



    /**
     * Performs avatar file uploading
     */
    public function uploadAvatar()
    {
        if (null === $this->avatarFile) {
            return;
        }

        // $this->avatar holds the new name for our file,
        // which was set upon calling prepareAvatarUpload()
        $this->avatarFile->move($this->getAvatarAbsoluteDir(), $this->avatar);

        // Do we have an old image to delete ?
        if (isset($this->avatarTemp)) {
            unlink($this->getAvatarAbsoluteDir().'/'.$this->avatarTemp);
            $this->avatarTemp = null;
        }

        $this->avatarFile = null;
    }



    /**
     * Deletes avatar file
     */
    public function deleteAvatar()
    {
        $filename = $this->getAvatarAbsolutePath();

        if (is_file($filename)) {
            unlink($filename);
        }
    }



    /**
     * Gets avatar absolute directory
     *
     * @return string
     */
    protected function getAvatarAbsoluteDir()
    {
        return __DIR__.'/../../../../web/'.$this->getAvatarWebDir();
    }



    /**
     * Gets avatar web directory
     *
     * @return string
     */
    protected function getAvatarWebDir()
    {
        return self::WEB_UPLOAD_DIR;
    }



    /**
     * Generates an unique name for uploaded avatar
     *
     * @param string $extension File extension
     */
    protected function generateAvatarName($extension)
    {
        return sha1(uniqid(mt_rand(), true)).'.'.$extension;
    }
}
