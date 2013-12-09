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
     * @ORM\Column(name="cancelled", type="boolean")
     */
    private $cancelled = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cancelled_at", type="datetime")
     */    
    private $cancelled_At;
        
    /**
     * @var boolean $refused
     *
     * @ORM\Column(name="refused", type="boolean")
     */
    private $refused = false;
        
    /**
     * @var datetime $refused_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="refused_at", nullable=true, type="datetime")
     */
    private $refused_at;

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
     * Set refused
     *
     * @param boolean $refused
     * @return RFP
     */
    public function setRefused($refused)
    {
        $this->refused_at = ($refused) ? new DateTime() : null;
        $this->refused = $refused;
    
        return $this;
    }

    /**
     * Get refused
     *
     * @return boolean 
     */
    public function getRefused()
    {
        return $this->refused;
    }

    /**
     * Set refused_at
     *
     * @param \DateTime $refusedAt
     * @return RFP
     */
    public function setRefusedAt($refusedAt)
    {
        $this->refused_at = $refusedAt;
    
        return $this;
    }

    /**
     * Get refused_at
     *
     * @return \DateTime 
     */
    public function getRefusedAt()
    {
        return $this->refused_at;
    }
    
    
    public function jsonSerialize() {
        $data = array(
            "id" => $this->getId(),
            "heading" => $this->getHeading(),
            "subheading" => $this->getSubheading(),
            //"order" => array( "id" => $this->getOrder()->getId() ),
            "refused" => $this->getRefused(),
            "refused_at" => $this->getRefusedAt(),
            "cancelled" => $this->getCancelled(),
            "cancelled_at" => $this->getCancelledAt(),
            "ready" => $this->getReady(),            
            "body" =>$this->getBody(),
            "created_at" =>$this->getCreatedAt()->format( "Y-m-d H:i:s" ),
        );
        
        return $data;
    }
}