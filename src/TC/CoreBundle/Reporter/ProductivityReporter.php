<?php

namespace TC\CoreBundle\Reporter;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Util\Date\Period;
use TC\CoreBundle\Util\Date\Week;

class ProductivityReporter extends AbstractReporter {

    /**
     * 
     * @param EntityManager $em
     * @param SecurityContext $securityContext
     */
    public function __construct( EntityManager $em, SecurityContext $securityContext ){
        parent::__construct( $em, $securityContext );
    }

    public function getFlow( Relation $relation ){
        $report = array(
            "labels"         => array( ),
            "completed_data" => array( ),
            "expected_data"  => array( ),
        );
        
        // Init Report
        $now = new DateTime();
        
        $start = new DateTime( "4 weeks ago" );
        $start = $start->modify( "last sunday" );

        $end = new DateTime( "4 weeks" );
        $end = $end->modify( "first saturday" );

        $interval = new DateInterval( "P7D" );

        $period = new Period( $start, $end );

        // Fetch Deliverables completed in that time period
        $deliverables = $this->em->getRepository( "TCCoreBundle:Deliverable" )->createQueryBuilder( "d" )
            ->join( "TCCoreBundle:Order", "o", "WITH", "o.id = d.order" )
            ->where( "o.relation = :relation" )
            ->andWhere( "( d.completed_at BETWEEN :start AND :end OR d.due_at BETWEEN :start AND :end)" )
            ->setParameter( "relation", $relation )
            ->setParameter( "start", $start )
            ->setParameter( "end", $end )
            ->getQuery()
            ->getResult();
        
        // Build Report
        foreach( $period->getDatePeriod( $interval ) as $key => $date ){
            $week = Week::createFromDate( $date );
            $label = $week->getShortLabel();

            $report[ "labels" ][ ] = $label;
            
            if( $date <= $now )
                $report[ "completed_data" ][ ] = 0;
            
            $report[ "expected_data" ][ ] = 0;
        }

        // Fill Report        
        foreach( $deliverables as $key => $deliverable ){

            if( $deliverable->getCompleted() && $deliverable->getCompletedAt() ){
                // Fill completed dataset
                $completedDate = $deliverable->getCompletedAt();

                if( $period->fitsIn( $completedDate ) ){
                    $week = Week::createFromDate( $completedDate );
                    $label = $week->getShortLabel();

                    $index = array_keys( $report[ "labels" ], $label );
                    $index = $index[ 0 ];

                    $report[ "completed_data" ][ $index ] += $deliverable->getTotal();
                }
            }

            if( $deliverable->getDueAt() ){
                // Fill expected dataset
                $expectedDate = $deliverable->getDueAt();

                if( $period->fitsIn( $expectedDate ) ){
                    $week = Week::createFromDate( $expectedDate );
                    $label = $week->getShortLabel();

                    $index = array_keys( $report[ "labels" ], $label );
                    $index = $index[ 0 ];

                    $report[ "expected_data" ][ $index ] += $deliverable->getTotal();
                }
            }
        };

        return $report;
    }

}

?>
