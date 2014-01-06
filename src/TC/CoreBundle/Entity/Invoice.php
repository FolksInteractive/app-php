<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * TC\CoreBundle\Entity\Invoice
 *
 * @ORM\Table(name="tc_invoice")
 * @ORM\Entity()
 */
class Invoice
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
     * @var DateTime $issued_at
     *
     * @ORM\Column(name="issued_at", type="datetime")
     */
    private $issued_at;

    /**
     * @var DateTime $due_at
     *
     * @ORM\Column(name="due_at", type="datetime")
     */
    private $due_at;
    
    /**
     * @var integer $no
     *
     * @ORM\Column(name="no", type="integer")
     */
    private $no;

    /**
     * @var ArrayCollection $deliverables
     * 
     * @ORM\ManyToMany(targetEntity="Deliverable", cascade={"all"}, inversedBy="invoice")
     * @ORM\JoinTable(name="tc_invoices_deliverables",
     *      joinColumns={@ORM\JoinColumn(name="invoice_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="deliverable_id",unique=true, referencedColumnName="id")}
     *      ) 
     * @ORM\OrderBy({"completedAt" = "DESC"})
     */
    private $deliverables;
    
    /**
     * @var Relation $relation 
     * 
     * @ORM\ManyToOne(targetEntity="Relation", inversedBy="invoices")
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
            $total += $deliverable->getTotal();
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
     * @return Invoice
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
     * @return Invoice
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
     * Set issued_at
     *
     * @param \DateTime $issuedAt
     * @return Invoice
     */
    public function setIssuedAt($issuedAt)
    {
        $this->issued_at = $issuedAt;
    
        return $this;
    }

    /**
     * Get issued_at
     *
     * @return \DateTime 
     */
    public function getIssuedAt()
    {
        return $this->issued_at;
    }

    /**
     * Set due_at
     *
     * @param \DateTime $dueAt
     * @return Invoice
     */
    public function setDueAt($dueAt)
    {
        $this->due_at = $dueAt;
    
        return $this;
    }

    /**
     * Get due_at
     *
     * @return \DateTime 
     */
    public function getDueAt()
    {
        return $this->due_at;
    }

    /**
     * Set no
     *
     * @param integer $no
     * @return Invoice
     */
    public function setNo($no)
    {
        $this->no = $no;
    
        return $this;
    }

    /**
     * Get no
     *
     * @return integer 
     */
    public function getNo()
    {
        return $this->no;
    }
}