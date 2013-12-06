<?php

namespace TC\CoreBundle\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use TC\CoreBundle\Validator\Constraints as TCAssert;

/**
 * @TCAssert\Relation()
 * @UniqueEntity(fields={"client","vendor"}, message="A relation already exists with this person.")
 * 
 * @ORM\Table(name="tc_relation")
 * @ORM\Entity()
 * 
 */
class Relation {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Workspace $creator
     * 
     * @Assert\Type(type="TC\CoreBundle\Entity\Workspace")
     * @Assert\NotNull(message="The creator of the new relation must be specified.")
     * @ORM\ManyToOne(targetEntity="Workspace", cascade={"persist"})
     */
    private $creator;
    
    /**
     *
     * @var Workspace $client
     * 
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="clientRelations", cascade={"persist", "remove"})
     */
    private $client;
    
    /**
     *
     * @var Workspace $vendor
     * 
     * @ORM\ManyToOne(targetEntity="Workspace", inversedBy="vendorRelations", cascade={"persist", "remove"})
     */
    private $vendor;
    
    /**
     * @var ArrayCollection $orders
     * 
     * @ORM\OneToMany(targetEntity="Order", mappedBy="relation", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    private $orders;
    
    /**
     * @var ArrayCollection $rfps
     * 
     * @ORM\OneToMany(targetEntity="RFP", mappedBy="relation", cascade={"persist", "remove"})
     * @ORM\OrderBy({"created_at" = "DESC"})
     */
    private $rfps;
    
    /**
     * @var ArrayCollection $bills
     * 
     * @ORM\OneToMany(targetEntity="Bill", mappedBy="relation", cascade={"persist", "remove"})
     * @ORM\OrderBy({"closedAt" = "DESC"})
     */
    private $closedBills;

    /**
     * @Assert\NotNull()
     * @ORM\OneToOne(targetEntity="Bill", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="openbill_id", referencedColumnName="id",onDelete="CASCADE")
     */
    private $openBill;

    /**
     * @var boolean $active
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var datetime $created_at
     *
     * @Assert\Type("\DateTime")
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $created_at;

    public function __construct() {
        $this->closedBills = new ArrayCollection();
        $this->orders = new ArrayCollection();
        $this->rfps = new ArrayCollection();
        $this->created_at = new DateTime();
        $this->openBill = new Bill( );
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set active
     *
     * @param boolean $active
     * @return Relation
     */
    public function setActive( $active ) {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function isActive() {
        return $this->active;
    }

    /**
     * Set created_at
     *
     * @param DateTime $created_at
     * @return Relation
     */
    public function setCreatedAt( $created_at ) {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return DateTime 
     */
    public function getCreatedAt() {
        return $this->created_at;
    }

    /**
     * Set creator
     *
     * @param Workspace $creator
     * @return Relation
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
     * Add orders
     *
     * @param Order $orders
     * @return Relation
     */
    public function addOrder( Order $orders ) {
        $this->orders[] = $orders;

        return $this;
    }

    /**
     * Remove orders
     *
     * @param Order $orders
     */
    public function removeOrder( Order $orders ) {
        $this->orders->removeElement( $orders );
    }

    /**
     * Get orders
     *
     * @return Collection 
     */
    public function getOrders() {
        return $this->orders;
    }

    /**
     * Get open orders
     *
     * @return Collection 
     */
    public function getOrdersOpen() {
        $orders = array();

        foreach ( $this->orders->toArray() as $key => $order ) {
            if ( $order->isOpen() && !$order->isApproved() ) {
                $orders[] = $order;
            }
        }

        return $orders;
    }
    
    /**
     * Get open orders
     *
     * @return Collection 
     */
    public function getOrdersTodo() {
        return $this->orders->filter( function($order){
            return ( $order->isApproved() && !$order->isCompleted() );
        });
    }

    /**
     * Get requests
     *
     * @return Collection 
     */
    public function getRequests() {
        $orders = array();

        foreach ( $this->orders->toArray() as $key => $order ) {
            if ( !$order->isOpen() && $this->getClient()  == $order->getCreator() ) {
                $orders[] = $order;
            }
        }

        return $orders;
    }

    /**
     * Get active
     *
     * @return boolean 
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Add closedBills
     *
     * @param Bill $closedBills
     * @return Relation
     */
    public function addClosedBill(Bill $closedBills)
    {
        $this->closedBills[] = $closedBills;
    
        return $this;
    }

    /**
     * Remove closedBills
     *
     * @param Bill $closedBills
     */
    public function removeClosedBill(Bill $closedBills)
    {
        $this->closedBills->removeElement($closedBills);
    }

    /**
     * Get closedBills
     *
     * @return Collection 
     */
    public function getClosedBills()
    {
        return $this->closedBills;
    }

    /**
     * Set openBill
     *
     * @param Bill $openBill
     * @return Relation
     */
    public function setOpenBill(Bill $openBill = null)
    {
        $this->openBill = $openBill;
    
        return $this;
    }

    /**
     * Get openBill
     *
     * @return Bill 
     */
    public function getOpenBill()
    {
        return $this->openBill;
    }


    /**
     * Set client
     *
     * @param Workspace $client
     * @return Relation
     */
    public function setClient(Workspace $client = null)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return Workspace 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set vendor
     *
     * @param Workspace $vendor
     * @return Relation
     */
    public function setVendor(Workspace $vendor = null)
    {
        $this->vendor = $vendor;
    
        return $this;
    }

    /**
     * Get vendor
     *
     * @return Workspace 
     */
    public function getVendor()
    {
        return $this->vendor;
    }

    /**
     * Add RFP
     *
     * @param RFP $rfp
     * @return Relation
     */
    public function addRFP(RFP $rfp)
    {
        $this->rfps[] = $rfp;
    
        return $this;
    }

    /**
     * Remove RFP
     *
     * @param RFP $rfp
     */
    public function removeRFP(RFP $rfp)
    {
        $this->rfps->removeElement($rfp);
    }

    /**
     * Get RFPs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRFPs()
    {
        return $this->rfps;
    }
}