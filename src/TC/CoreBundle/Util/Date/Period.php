<?php

namespace TC\CoreBundle\Util\Date;

use DateInterval;
use DatePeriod;
use DateTime;

class Period {
    
    /* 
     * Classes inherited from DatePeriod class cannot use their declared properties
     * https://bugs.php.net/bug.php?id=65672
     * 
     * That's why I use $s and $e
    */ 
    
    /**
     * @var DateTime
     */
    public $start;

    /**
     * @var DateTime
     */
    public $end;
        

    public function __construct( DateTime $start, DateTime $end ){
        $this->start = $start;
        $this->end = $end;
        
    }

    /**
     * 
     * @param DateInterval $interval
     * @return DatePeriod
     */
    public function getDatePeriod( DateInterval $interval = null){
        if( $interval == null )
            $interval = new DateInterval( "P1D" );
        
        return new DatePeriod($this->start, $interval, $this->end);
    }
    
    /**
     * 
     * @param DateTime $date
     * @return boolean
     */
    public function fitsIn( DateTime $date ){
        return ( $this->s <= $date && $this->e >=$date );
    }

}

?>
