<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * @ORM\Table(name="tc_comment")
 * @ORM\Entity
 */
class Comment implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\generatedValue(strategy="AUTO")
     */
    protected $id;
  
    /**
     * Comment text
     * @var string
     * 
     * @Assert\NotBlank()
     * @ORM\Column(type="text")
     */
    protected $body;

    /**
     * @var DateTime
     * 
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;
    
    /**
     * Thread of this comment
     *
     * @var Thread
     * 
     * @Assert\NotNull()
     * @ORM\ManyToOne(targetEntity="Thread", inversedBy="comments", cascade={"persist", "remove"})
     */
    protected $thread;
    
    /**
     * Author of the comment
     * 
     * @Assert\NotNull()
     * @var Workspace
     * 
     * @ORM\ManyToOne(targetEntity="Workspace")
     */
    protected $author;
    
    /**
     * CONSTRUCTOR
     */
    public function __construct()
    {
        $this->createdAt = new DateTime();
    }
    
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
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    
    /**
     * @param string $body
     */
    public function setBody($body)
    {
        $this->body = $body;
    }
    
    /**
     *
     * @param Workspace $author 
     */

    public function setAuthor(Workspace $author)
    {
        $this->author = $author;
    }
    
    /**
     * @return TC\CoreBundle\Entity\Workspace
     */
    public function getAuthor()
    {
        return $this->author;
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    /**
     * Sets the creation date
     * @param DateTime $createdAt
     */
    public function setCreatedAt(DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }
    
    /**
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }

    /**
     * @param Thread $thread
     *
     * @return void
     */
    public function setThread(Thread $thread)
    {
        $this->thread = $thread;
    }
    
    
    public function jsonSerialize() {
        $data = array(
            "id" => $this->getId(),
            "body" =>$this->getBody(),
            "createdAt" =>$this->getCreatedAt()->format( "Y-m-d H:i:s" ),
            "author" =>$this->getAuthor()->jsonSerialize(),
        );
        
        return $data;
    }
}