<?php

namespace TC\CoreBundle\Util\Date;

use DateInterval;
use DateTime;
use InvalidArgumentException;

class Week extends DatePeriod {
    
    public function __construct( $start, $end ){
        
        if( $start->format( "w" ) != "0" )
            throw new InvalidArgumentException("Start date must be a Sunday");


        if( $end->format( "w" ) != "6" )
            throw new InvalidArgumentException("End date must be a Saturday");
    
        parent::__construct( $start, new DateInterval("P1D"), $end );
    }
    
    public function getShortLabel(){      

        $label = $this->start->format( "d" ) . " - " . $this->end->format( "d" );

        if( $this->start > $this->end )
            $label . " " . $this->end->format( "m" ); 
        
        return $label;
    }
    
    public static function createFromDate( DateTime $date ){
        
        $start = clone $date;
        $end = clone $date;

        if( $start->format( "w" ) != "0" )
            $start->modify( 'last sunday' );


        if( $end->format( "w" ) != "6" )
            $end->modify( 'next saturday' );
        
        return new Week($start, $end);
    }
}

?>
