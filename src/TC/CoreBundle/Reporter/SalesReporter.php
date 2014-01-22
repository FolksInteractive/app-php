<?php

namespace TC\CoreBundle\Reporter;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Exception;
use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Relation;

class SalesReporter extends AbstractReporter {

    /**
     * 
     * @param EntityManager $em
     * @param SecurityContext $securityContext
     */
    public function __construct( EntityManager $em, SecurityContext $securityContext ){
        parent::__construct( $em, $securityContext );
    }

    /**
     * Count the number of pending RFPs
     * @param Relation $relation
     * @return integer
     */
    public function countPendingRFPs( Relation $relation = null ){   
        
        if( !$relation )
            return 0;
        
        return $this->getPendingRFPs( $relation )->count();
    }

    /**
     * Fetches the list of RFP without a proposal
     * @param Relation $relation
     * @return ArrayCollection
     */
    public function getPendingRFPs( Relation $relation = null ){
        try{
            $ordersQB = $this->em->createQueryBuilder();
            $ordersQB
                ->select( 'DISTINCT IDENTITY(o.rfp)' )
                ->from( "TCCoreBundle:Order", "o" )
                ->join("TCCoreBundle:RFP", '_r', 'WITH', '_r = o.rfp')
                ->where( "o.rfp IS NOT NULL" )
                ->andWhere( "r.relation = :relation" );
            
            $rfpsQB = $this->em->getRepository( "TCCoreBundle:RFP" )->createQueryBuilder( "r" );

            $rfps = $rfpsQB
                ->where( "r.relation = :relation" )
                ->andWhere( "r.ready = true" )
                ->andWhere( $rfpsQB->expr()->notIn( "r.id", $ordersQB->getDQL() ) )
                ->setParameter( "relation", $relation )
                ->getQuery()
                ->getResult();
        } catch( Exception $e ){
            $rfps = [ ];
        }

        return new ArrayCollection( $rfps );
    }


    /**
     * Count the number of pending Orders
     * @param Relation $relation
     * @return integer
     */
    public function countPendingOrders( Relation $relation = null ){       
        
        if( !$relation )
            return 0;
        
        return $this->getPendingOrders( $relation )->count();
    }

    /**
     * Gets the total of all the pending orders
     * @param Relation $relation
     * @return integer
     */
    public function getPendingOrdersAmount( Relation $relation = null ){
        
        if( !$relation )
            return 0;
        
        $total = 0;
        
        $orders = $this->getPendingOrders( $relation );
        
        foreach( $orders as $key => $order ){
            $total += $order->getTotal();
        }
        
        return $total;
    }


    /**
     * Fetches the list of Orders waiting for an approval
     * @param Relation $relation
     * @return ArrayCollection
     */
    public function getPendingOrders( Relation $relation ){
        $this->em->getRepository( "TCCoreBundle:RFP" )->createQueryBuilder( "r" );
        try{
            $orders = $this->em->getRepository( "TCCoreBundle:Order" )->createQueryBuilder( "o" )
                ->where( "r.relation = :relation" )
                ->andWhere( "o.ready = true" )
                ->andWhere( "o.purchased = false" )
                ->setParameter( "relation", $relation )
                ->getQuery()
                ->getResult();
        } catch( Exception $e ){
            $orders = [ ];
        }

        return new ArrayCollection( $orders );
    }

}

?>
