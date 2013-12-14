<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RFP
 *
 * @ORM\Table(name="tc_rfp")
 * @ORM\Entity
 */
class RFP implements JsonSerializable
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
    private $created_at;
        
    /**
     * @var boolean $cancelled
     *
     * @ORM\Column(name="cancelled", type="boolean", options={"default" = 0})
     */
    private $cancelled = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cancelled_at", type="datetime", nullable=true)
     */    
    private $cancelled_At;
        
    /**
     * @var boolean $declined
     *
     * @ORM\Column(name="declined", type="boolean", options={"default" = 0})
     */
    private $declined = false;
        
    /**
     * @var datetime $declined_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="declined_at", nullable=true, type="datetime", nullable=true)
     */
    private $declined_at;

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
    {
        $this->created_at = new \DateTime();
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
     * @param \TC\CoreBundle\Entity\Order $order
     * @return RFP
     */
    public function setOrder(\TC\CoreBundle\Entity\Order $order = null)
    {
        $this->order = $order;
    
        return $this;
    }

    /**
     * Get order
     *
     * @return \TC\CoreBundle\Entity\Order 
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $created_at
     * @return RFP
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    
        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set cancelled
     *
     * @param boolean $cancelled
     * @return RFP
     */
    public function setCancelled($cancelled)
    {        
        $this->cancelled_at = ($cancelled) ? new DateTime() : null;
        $this->cancelled = $cancelled;
        
        return $this;
    }

    /**
     * Get cancelled
     *
     * @return boolean 
     */
    public function getCancelled()
    {
        return $this->cancelled;
    }

    /**
     * Set cancelled_At
     *
     * @param \DateTime $cancelledAt
     * @return RFP
     */
    public function setCancelledAt($cancelledAt)
    {
        $this->cancelled_At = $cancelledAt;
    
        return $this;
    }

    /**
     * Get cancelled_At
     *
     * @return \DateTime 
     */
    public function getCancelledAt()
    {
        return $this->cancelled_At;
    }

    /**
     * Set declined
     *
     * @param boolean $declined
     * @return RFP
     */
    public function setDeclined($declined)
    {
        $this->declined_at = ($declined) ? new DateTime() : null;
        $this->declined = $declined;
    
        return $this;
    }

    /**
     * Get declined
     *
     * @return boolean 
     */
    public function getDeclined()
    {
        return $this->declined;
    }

    /**
     * Set declined_at
     *
     * @param \DateTime $declinedAt
     * @return RFP
     */
    public function setDeclinedAt($declinedAt)
    {
        $this->declined_at = $declinedAt;
    
        return $this;
    }

    /**
     * Get declined_at
     *
     * @return \DateTime 
     */
    public function getDeclinedAt()
    {
        return $this->declined_at;
    }
    
    
    public function jsonSerialize() {
        $data = array(
            "id" => $this->getId(),
            "heading" => $this->getHeading(),
            "subheading" => $this->getSubheading(),
            //"order" => array( "id" => $this->getOrder()->getId() ),
            "declined" => $this->getDeclined(),
            "declined_at" => $this->getDeclinedAt(),
            "cancelled" => $this->getCancelled(),
            "cancelled_at" => $this->getCancelledAt(),
            "ready" => $this->getReady(),            
            "body" =>$this->getBody(),
            "created_at" =>$this->getCreatedAt()->format( "Y-m-d H:i:s" ),
        );
        
        return $data;
    }
}