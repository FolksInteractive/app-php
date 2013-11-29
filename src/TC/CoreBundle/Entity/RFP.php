<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RFP
 *
 * @ORM\Table(name="tc_rfp")
 * @ORM\Entity
 */
class RFP
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
     * @var Thread thread
     * 
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="Thread", cascade={"persist", "remove"})
     */
    private $thread;

    /**
     * @var boolean $approved
     *
     * @ORM\Column(name="heading", type="string")
     */
    private $heading;
          
    /**
     * @var boolean $approved
     *
     * @ORM\Column(name="subheading", type="string", nullable=true)
     */
    private $subheading;
    
    /**
     * @var array
     *
     * @ORM\Column(name="body", type="array")
     */
    private $body = array();

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ready", type="boolean")
     */
    private $ready = false;

    /**
     * @var Workspace $creator
     * 
     * @ORM\ManyToOne(targetEntity="Workspace")
     */
    private $creator;
    
    /**
     * @var Relation $relation
     * 
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="orders", cascade={"persist"})
     */
    private $relation;
    
    /**
     * @var Order $order
     * 
     * @ORM\OneToOne(targetEntity="Order", mappedBy="rfp", cascade={"persist"})
     * 
     */
    private $order;
    
    public function __construct()
    {;
        $this->createdAt = new \DateTime();
    }
    
    public function __toString() {
        return $this->heading;
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
     * Set body
     *
     * @param array $body
     * @return RequestProposal
     */
    public function setBody($body)
    {
        $this->body = $body;
    
        return $this;
    }

    /**
     * Get body
     *
     * @return array 
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return RequestProposal
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set ready
     *
     * @param boolean $ready
     * @return RequestProposal
     */
    public function setReady($ready)
    {
        $this->ready = $ready;
    
        return $this;
    }

    /**
     * Get ready
     *
     * @return boolean 
     */
    public function isReady()
    {
        return $this->ready;
    }

    /**
     * Set heading
     *
     * @param string $heading
     * @return RFP
     */
    public function setHeading($heading)
    {
        $this->heading = $heading;
    
        return $this;
    }

    /**
     * Get heading
     *
     * @return string 
     */
    public function getHeading()
    {
        return $this->heading;
    }

    /**
     * Set subheading
     *
     * @param string $subheading
     * @return RFP
     */
    public function setSubheading($subheading)
    {
        $this->subheading = $subheading;
    
        return $this;
    }

    /**
     * Get subheading
     *
     * @return string 
     */
    public function getSubheading()
    {
        return $this->subheading;
    }

    /**
     * Set creator
     *
     * @param \TC\CoreBundle\Entity\Workspace $creator
     * @return RFP
     */
    public function setCreator(\TC\CoreBundle\Entity\Workspace $creator = null)
    {
        $this->creator = $creator;
    
        return $this;
    }

    /**
     * Get creator
     *
     * @return \TC\CoreBundle\Entity\Workspace 
     */
    public function getCreator()
    {
        return $this->creator;
    }
    
    /**
     * Get thread
     *
     * @return Thread
     */
    public function getThread()
    {
        return $this->thread;
    }
    
    /**
     * Set thread
     *
     * @param Thread  $thread
     */
    public function setThread(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Set relation
     *
     * @param Relation $relation
     */
    public function setRelation(Relation $relation)
    {
        $this->relation = $relation;
    }

    /**
     * Get relation
     *
     * @return Relation 
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Get ready
     *
     * @return boolean 
     */
    public function getReady()
    {
        return $this->ready;
    }

    /**
     * Set order
     *
     * @param \TC\CoreBundle\Entity\RFP $order
     * @return RFP
     */
    public function setOrder(\TC\CoreBundle\Entity\RFP $order = null)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return \TC\CoreBundle\Entity\RFP 
     */
    public function getOrder()
    {
        return $this->order;
    }
}