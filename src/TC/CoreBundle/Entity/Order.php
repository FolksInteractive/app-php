<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * @TCAssert\Order()
 * @ORM\Table(name="tc_order")
 * @ORM\Entity()
 */
class Order
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
          
    /**
     * @var string $approved
     *
     * @ORM\Column(name="heading", type="string")
     */
    private $heading;
          
    /**
     * @var string $approved
     *
     * @ORM\Column(name="subheading", type="string", nullable=true)
     */
    private $subheading;
    
    /**
     * @var array $offer
     *
     * @ORM\Column(name="offer", type="array", nullable=true)
     */
    private $offer = array();
    
    /**
     * @var datetime $created_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;
          
    /**
     * @var boolean $ready
     *
     * @ORM\Column(name="ready", type="boolean", options={"default" = 0})
     */
    private $ready = false;
          
    /**
     * @var boolean $approved
     *
     * @ORM\Column(name="approved", type="boolean", options={"default" = 0})
     */
    private $approved = false;
    
    /**
     * @var datetime $approved_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="approved_at", nullable=true, type="datetime")
     */
    private $approved_at;
    
    /**
     * @var boolean $completed
     *
     * @ORM\Column(name="completed", type="boolean", options={"default" = 0})
     */
    private $completed = false;
        
    /**
     * @var datetime $completed_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="completed_at", nullable=true, type="datetime")
     */
    private $completed_at;
        
    /**
     * @var boolean $billed
     *
     * @ORM\Column(name="billed", type="boolean", options={"default" = 0})
     */
    private $billed = false;
        
    /**
     * @var datetime $billed_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="billed_at", nullable=true, type="datetime")
     */
    private $billed_at;
        
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
     * @ORM\Column(name="declined_at", nullable=true, type="datetime")
     */
    private $declined_at;
        
    /**
     * @var boolean $cancelled
     *
     * @ORM\Column(name="cancelled", type="boolean", options={"default" = 0})
     */
    private $cancelled = false;
        
    /**
     * @var datetime $cancelled_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="cancelled_at", nullable=true, type="datetime")
     */
    private $cancelled_at;
                
    /**
     * @var Relation $relation
     * 
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="orders", cascade={"persist"})
     */
    private $relation;
        
    /**
     * @var Workspace $creator
     * 
     * @ORM\ManyToOne(targetEntity="Workspace")
     */
    private $creator;
    
    /**
     * @var Thread thread
     * 
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="Thread", cascade={"persist", "remove"})
     */
    private $thread;
    
    /**
     * @var ArrayCollection $deliverables
     * 
     * @ORM\OneToMany(targetEntity="Deliverable", mappedBy="order", cascade={"persist", "remove"})
     */
    protected $deliverables;
    
    /**
     * @var RFP $rfp
     * 
     * @ORM\OneToOne(targetEntity="RFP", inversedBy="order", cascade={"persist"})
     * @ORM\JoinColumn(unique=true)
     */
    protected $rfp;
    
    public function __construct()
    {
        $this->deliverables = new ArrayCollection();
        $this->created_at = new DateTime();
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
     * Set created_at
     *
     * @param string $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get created_at
     *
     * @return string 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
    
    /**
     * Get total
     *
     * @return double 
     */
    public function getTotal()
    {
        
        if( $this->deliverables->count() <= 0 )
            return null;
        
        
        $total = 0;
        foreach( $this->deliverables as $key=>$deliverable ){
            $total += $deliverable->getTotal() ;
        }
        
        return $total;
    }
    
    /**
     * Set approved
     *
     * @param boolean $approved
     */
    public function setApproved($approved)
    {
        $this->approved_at = ($approved) ? new DateTime() : null;
        $this->approved = $approved;
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function isApproved()
    {
        return $this->approved;
    }

    /**
     * Get created_at
     *
     * @return string 
     */
    public function getApprovedAt()
    {
        return $this->approved_at;
    }   
    /**
     * Set completed
     *
     * @param boolean $completed
     */
    public function setCompleted($completed)
    {
        $this->completed_at = ($completed) ? new DateTime() : null;
        $this->completed = $completed;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function isCompleted()
    {
        return $this->completed;
    }
    
    /**
     * Set billed
     *
     * @param boolean $billed
     */
    public function setBilled($billed)
    {
        $this->billed_at = ($billed) ? new DateTime() : null;
        $this->billed = $billed;
    }

    /**
     * Get billed
     *
     * @return boolean 
     */
    public function isBilled()
    {
        return $this->billed;
    }
    
    /**
     * set creator
     * 
     * @param \TC\CoreBundle\Entity\Workspace $creator
     */
    public function setCreator(Workspace $creator)
    {
        $this->creator = $creator;
    }

    /**
     * Get creator
     *
     * @return Workspace
     */
    public function getCreator()
    {
        return $this->creator;
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
    
    public function __toString(){
      return $this->heading;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function isActive()
    {
        return ( $this->active && $this->relation->isActive() );
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
     * Add deliverable
     *
     * @param Deliverable $deliverable
     */
    public function addDeliverable(Deliverable $deliverable)
    {
        $deliverable->setOrder($this);
        $this->deliverables[] = $deliverable;
    }
    
    public function removeDeliverable(Deliverable $deliverable)
    {
        $this->deliverables->removeElement($deliverable);
    }

    /**
     * Get list of deliverables
     *
     * @return Collection 
     */
    public function getDeliverables()
    {
        return $this->deliverables;
    }

    /**
     * Get list of deliverables
     *
     * @return Collection 
     */
    public function getDeliverablesTodo()
    {
        return $this->deliverables->filter( function($deliverable){
            return !$deliverable->isCompleted();
        });
    }

    /**
     * Get list of deliverables
     *
     * @return Collection 
     */
    public function getDeliverablesCompleted()
    {
        return $this->deliverables->filter( function($deliverable){
            return $deliverable->isCompleted();
        });
    }

    /**
     * Get approved
     *
     * @return boolean 
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set approved_at
     *
     * @param DateTime $approvedAt
     * @return Order
     */
    public function setApprovedAt($approvedAt)
    {
        $this->approved_at = $approvedAt;
    
        return $this;
    }

    /**
     * Get completed
     *
     * @return boolean 
     */
    public function getCompleted()
    {
        return $this->completed;
    }

    /**
     * Set completed_at
     *
     * @param DateTime $completedAt
     * @return Order
     */
    public function setCompletedAt($completedAt)
    {
        $this->completed_at = $completedAt;
    
        return $this;
    }

    /**
     * Get completed_at
     *
     * @return DateTime 
     */
    public function getCompletedAt()
    {
        return $this->completed_at;
    }

    /**
     * Get billed
     *
     * @return boolean 
     */
    public function getBilled()
    {
        return $this->billed;
    }

    /**
     * Set billed_at
     *
     * @param DateTime $billedAt
     * @return Order
     */
    public function setBilledAt($billedAt)
    {
        $this->billed_at = $billedAt;
    
        return $this;
    }

    /**
     * Get billed_at
     *
     * @return DateTime 
     */
    public function getBilledAt()
    {
        return $this->billed_at;
    }

    /**
     * Set ready
     *
     * @param boolean $ready
     * @return Order
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
    public function getReady()
    {
        return $this->ready;
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
     * Get open
     *
     * @return boolean 
     */
    public function isOpen()
    {
        return ($this->offer || $this->deliverables->count()>0);
    }

    /**
     * Set offer
     *
     * @param array $offer
     * @return Order
     */
    public function setOffer($offer)
    {
        $this->offer = $offer;
    
        return $this;
    }

    /**
     * Get offer
     *
     * @return array 
     */
    public function getOffer()
    {
        if( $this->offer == null )
            return array();
        
        return $this->offer;
    }

    /**
     * Set heading
     *
     * @param string $heading
     * @return Order
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
     * @return Order
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
     * Set rfp
     *
     * @param RFP $rfp
     * @return Order
     */
    public function setRfp(RFP $rfp = null)
    {
        $this->rfp = $rfp;
    
        return $this;
    }

    /**
     * Get rfp
     *
     * @return RFP 
     */
    public function getRfp()
    {
        return $this->rfp;
    }

    /**
     * Set declined
     *
     * @param boolean $declined
     * @return Order
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
     * @return Order
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

    /**
     * Set cancelled
     *
     * @param boolean $cancelled
     * @return Order
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
     * Set cancelled_at
     *
     * @param \DateTime $cancelledAt
     * @return Order
     */
    public function setCancelledAt($cancelledAt)
    {
        $this->cancelled_at = $cancelledAt;
    
        return $this;
    }

    /**
     * Get cancelled_at
     *
     * @return \DateTime 
     */
    public function getCancelledAt()
    {
        return $this->cancelled_at;
    }
}