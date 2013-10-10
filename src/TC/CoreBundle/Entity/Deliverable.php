<?php

namespace TC\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * Deliverable
 *
 * @TCAssert\Deliverable()
 * @ORM\Table(name="tc_deliverable")
 * @ORM\Entity
 */
class Deliverable
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
     * @Assert\NotNull(message="You must give a name to the deliverable.")
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(name="descripiton", type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @var Order
     * 
     * @Assert\NotNull(message="You must assign an Order to the Delivery" )
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="deliverables", cascade={"persist"})
     */
    private $order;
 
    /**
     * @var double
     *
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     *      
     * @Assert\Type(
     *     type="double", 
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @ORM\Column(name="cost", type="float")
     */   
    private $cost = -1;
    
    /**
     *
     * @var integer
     * 
     *      
     * @Assert\Type(
     *     type="integer", 
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     * @Assert\GreaterThan(
     *     value = 0
     * )
     * 
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity = 1;

    /**
     * @var Workspace $creator
     * 
     * @Assert\Type(type="TC\CoreBundle\Entity\Workspace")
     * @ORM\ManyToOne(targetEntity="Workspace", cascade={"persist"})
     * @ORM\JoinColumn(name="creatorWorkspace_id", referencedColumnName="id")
     */
    private $creator;
    
    /**
     * @var boolean $completed
     *
     * @ORM\Column(name="completed", type="boolean")
     */
    private $completed = false;
    
    
    /**
     * @var datetime $completedAt
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="completed_at", nullable=true, type="datetime")
     */
    private $completedAt;
    
    /**
     * @var boolean $billed
     *
     * @ORM\Column(name="billed", type="boolean")
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
     * @return Deliverable
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
     * Get order
     * 
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }
    
    /**
     * Set order
     * 
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Set cost
     *
     * @param float $cost
     * @return Deliverable
     */
    public function setCost( $cost)
    {
        $this->cost = (double) $cost;
    
        return $this;
    }

    /**
     * Get cost
     *
     * @return float 
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     * @return Deliverable
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    
        return $this;
    }

    /**
     * Get quantity
     *
     * @return integer 
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set creator
     *
     * @param Workspace $creator
     * @return Deliverable
     */
    public function setCreator( Workspace $creator = null ) {
        $this->creator = $creator;

        return $this;
    }

    /**
     * Get creator
     *
     * @return Workspace 
     */
    public function getCreator() {
        return $this->creator;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Deliverable
     */
    public function setDescription($description)
    {
        $this->description = $description;
    
        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set completed
     *
     * @param boolean $completed
     */
    public function setCompleted($completed)
    {
        $this->setCompletedAt(new \DateTime());
        $this->completed = $completed;
    }
    
    /**
     * Is completed
     *
     * @return boolean 
     */
    public function isCompleted()
    {
        return $this->completed;
    }

    /**
     * Set completedAt
     *
     * @param \DateTime $completedAt
     * @return Deliverable
     */
    public function setCompletedAt($completedAt)
    {
        $this->completedAt = $completedAt;
    
        return $this;
    }

    /**
     * Get completedAt
     *
     * @return \DateTime 
     */
    public function getCompletedAt()
    {
        return $this->completedAt;
    }
    
    /**
     * Is completed
     *
     * @return boolean 
     */
    public function isBilled()
    {
        return $this->billed;
    }

    /**
     * Set billed_at
     *
     * @param \DateTime $billedAt
     * @return Deliverable
     */
    public function setBilledAt($billedAt)
    {
        $this->billed_at = $billedAt;
    
        return $this;
    }

    /**
     * Get billed_at
     *
     * @return \DateTime 
     */
    public function getBilledAt()
    {
        return $this->billed_at;
    }
}