<?php

namespace TC\CoreBundle\Util\Date;

use DateInterval;
use DatePeriod;
use DateTime;
use InvalidArgumentException;

class Week extends Period {
    
    public function __construct( DateTime $start, DateTime $end ){
        
        if( $start->format( "w" ) != "0" )
            throw new InvalidArgumentException("Start date must be a Sunday");


        if( $end->format( "w" ) != "6" )
            throw new InvalidArgumentException("End date must be a Saturday");
    
        parent::__construct( $start, $end );
    }
    
    /**
     * @return string
     */
    public function getShortLabel(){      

        $label = $this->start->format( "d" ) . " - " . $this->end->format( "d" );

        if( $this->start > $this->end)
            $label . " " . $this->end->format( "m" ); 
        
        return $label;
    }
    
    /**
     * @param DateTime $date
     * @return Week
     */
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
