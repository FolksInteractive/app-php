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
     * @var DateTime $created_at
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

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
        $this->created_at = new DateTime();
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
     * Set created_at
     *
     * @param DateTime $created_at
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }

    /**
     * Get created_at
     *
     * @return DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
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
}