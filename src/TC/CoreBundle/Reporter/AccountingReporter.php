<?php

namespace TC\CoreBundle\Reporter;

use DateInterval;
use DateTime;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Util\Date\Period;
use TC\CoreBundle\Util\Date\Week;

class AccountingReporter extends AbstractReporter {

    /**
     * 
     * @param EntityManager $em
     * @param SecurityContext $securityContext
     */
    public function __construct( EntityManager $em, SecurityContext $securityContext ){
        parent::__construct( $em, $securityContext );
    }
    
    public function getOverview( Relation $relation = null ){
        $report = array(
            "labels"         => array( ),
            "this_year_data" => array( ),
            "last_year_data"  => array( ),
        );

        if( !$relation )
            return $report;
        
        // Init Report
        $now = new DateTime();

        $start = new DateTime( "first day of january" );

        $end = new DateTime( "last day of december" );

        $interval = new DateInterval( "P1M" );

        $period = new Period( $start, $end );


        // Fetch Deliverables completed in that time period
        $deliverables = $this->em->getRepository( "TCCoreBundle:Invoice" )->createQueryBuilder( "i" )
            ->where( "i.relation = :relation" )
            ->andWhere( "( i.issued_at BETWEEN :start AND :end" )
            ->setParameter( "relation", $relation )
            ->setParameter( "start", $start )
            ->setParameter( "end", $end )
            ->getQuery()
            ->getResult();
        
    }

}

?>
