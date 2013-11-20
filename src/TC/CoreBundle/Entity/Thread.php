<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Table(name="tc_thread")
 * @ORM\Entity
 */
class Thread implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * Tells if new comments can be added in this thread
     *
     * @var bool
     * @ORM\Column(type="boolean")
     */
    protected $isCommentable = true;

    /**
     * Denormalized date of the last comment
     *
     * @var DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $lastComment_at = null;
    
    /**
     * @var ArrayCollection $tasks
     * 
     * @ORM\OneToMany(targetEntity="Comment", mappedBy="thread", cascade={"persist", "remove"})
     * @ORM\OrderBy({"createdAt" = "ASC"})
     */
    protected $comments;
    
    /**
     * @var ArrayCollection $tasks
     * 
     * @ORM\ManyToMany(targetEntity="Workspace", cascade={"persist", "remove"})
     * @ORM\JoinTable(name="tc_thread_followers")
     */
    protected $followers;
    
    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->followers = new ArrayCollection();
    }
    
    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * @return bool
     */
    public function isCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * @param  bool
     * @return null
     */
    public function setCommentable($isCommentable)
    {
        $this->isCommentable = (bool) $isCommentable;
    }

    /**
     * Gets the number of comments
     *
     * @return integer
     */
    public function getNumComments()
    {
        return $this->comments->count();
    }

    /**
     * @return DateTime
     */
    public function getLastCommentAt()
    {
        return $this->lastComment_at;
    }

    /**
     * @param  DateTime
     * @return null
     */
    public function setLastCommentAt($lastCommentAt)
    {
        $this->lastComment_at = $lastCommentAt;
    }
    
    /**
     * Add comment
     *
     * @param Comment $comment
     */
    public function addComment(Comment $comment)
    {
        $comment->setThread($this);
        $this->lastComment_at = new DateTime();
        $this->comments[] = $comment;
    }

    /**
     * Get list of comments
     *
     * @return Collection 
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set isCommentable
     *
     * @param boolean $isCommentable
     * @return Thread
     */
    public function setIsCommentable($isCommentable)
    {
        $this->isCommentable = $isCommentable;
    
        return $this;
    }

    /**
     * Get isCommentable
     *
     * @return boolean 
     */
    public function getIsCommentable()
    {
        return $this->isCommentable;
    }

    /**
     * Remove comments
     *
     * @param \TC\CoreBundle\Entity\Comment $comments
     */
    public function removeComment(\TC\CoreBundle\Entity\Comment $comments)
    {
        $this->comments->removeElement($comments);
    }
    
    /**
     * @return array
     */
    public function jsonSerialize() {
        $data = array(
            "id" => $this->getId(),
            "lastCommentAt" => $this->getLastCommentAt() ? $this->getLastCommentAt()->format( "Y-m-d H:i:s" ):null,           
            "numComments" => $this->getNumComments(),
            "isCommentable" => $this->getIsCommentable(),
            "comments" => $this->getComments()->toArray()
        );
        return $data;
    }

    /**
     * Add followers
     *
     * @param \TC\CoreBundle\Entity\Workspace $followers
     * @return Thread
     */
    public function addFollower(\TC\CoreBundle\Entity\Workspace $followers)
    {
        $this->followers[] = $followers;
    
        return $this;
    }

    /**
     * Remove followers
     *
     * @param \TC\CoreBundle\Entity\Workspace $followers
     */
    public function removeFollower(\TC\CoreBundle\Entity\Workspace $followers)
    {
        $this->followers->removeElement($followers);
    }

    /**
     * Get followers
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFollowers()
    {
        return $this->followers;
    }
}