<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TC\CoreBundle\Entity\Bill
 *
 * @ORM\Table(name="tc_bill")
 * @ORM\Entity()
 */
class Bill
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
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var ArrayCollection $deliverables
     * 
     * @ORM\ManyToMany(targetEntity="Deliverable", cascade={"all"}, inversedBy="bill")
     * @ORM\JoinTable(name="tc_bills_deliverables",
     *      joinColumns={@ORM\JoinColumn(name="bill_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="deliverable_id",unique=true, referencedColumnName="id")}
     *      ) 
     * @ORM\OrderBy({"completedAt" = "DESC"})
     */
    private $deliverables;
    
    /**
     * @var Relation $relation 
     * 
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="bills")
     */
    private $relation;
 
    /**
     * @var boolean $closed
     *
     * @ORM\Column(name="closed", type="boolean")
     */    
    private $closed = false;

    /**
     * @var DateTime $createdAt
     *
     * @ORM\Column(name="closed_at", nullable=true, type="datetime")
     */    
    private $closedAt;
    

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __construct( )
    {
        $this->createdAt = new DateTime();
        $this->deliverables = new ArrayCollection();        
    }

    /**
     * Get total
     *
     * @return float 
     */
    public function getTotal()
    {
        
        if( $this->deliverables->count() <= 0 )
            return null;
        
        $list = $this->deliverables->toArray();
        $total = 0;
        for($i = 0; $i < count($list); ++$i) {
            /* @var $deliverable Deliverable */
            $deliverable = $list[$i];
            $total += ($deliverable->getCost()) ? $deliverable->getCost() : 0 ;
        }
        return $total;
    }

    /**
     * Set createdAt
     *
     * @param DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get createdAt
     *
     * @return DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Add deliverables
     *
     * @param \TC\CoreBundle\Entity\Deliverable $deliverables
     * @return Bill
     */
    public function addDeliverable(\TC\CoreBundle\Entity\Deliverable $deliverables)
    {
        $this->deliverables[] = $deliverables;
    
        return $this;
    }

    /**
     * Remove deliverables
     *
     * @param \TC\CoreBundle\Entity\Deliverable $deliverables
     */
    public function removeDeliverable(\TC\CoreBundle\Entity\Deliverable $deliverables)
    {
        $this->deliverables->removeElement($deliverables);
    }

    /**
     * Get deliverables
     *
     * @return Collection 
     */
    public function getDeliverables()
    {
        return $this->deliverables;
    }

    /**
     * Set relation
     *
     * @param \TC\CoreBundle\Entity\Relation $relation
     * @return Bill
     */
    public function setRelation(\TC\CoreBundle\Entity\Relation $relation = null)
    {
        $this->relation = $relation;
    
        return $this;
    }

    /**
     * Get relation
     *
     * @return \TC\CoreBundle\Entity\Relation 
     */
    public function getRelation()
    {
        return $this->relation;
    }

    /**
     * Set closed
     *
     * @param boolean $closed
     * @return Bill
     */
    public function setClosed($closed)
    {
        $this->closedAt = new DateTime();
        $this->closed = $closed;
    
        return $this;
    }

    /**
     * Get closed
     *
     * @return boolean 
     */
    public function getClosed()
    {
        return $this->closed;
    }
    

    /**
     * Set closedAt
     *
     * @param DateTime $closedAt
     */
    public function setClosedAt($closedAt)
    {
        $this->closedAt = $closedAt;
    }

    /**
     * Get closedAt
     *
     * @return DateTime 
     */
    public function getClosedAt()
    {
        return $this->closedAt;
    }
}