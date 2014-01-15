<?php

namespace TC\CoreBundle\Util\Date;

use DatePeriod as BaseDatePeriod;
use DateTime;

class DatePeriod extends BaseDatePeriod {
    
    /* 
     * Classes inherited from DatePeriod class cannot use their declared properties
     * https://bugs.php.net/bug.php?id=65672
     * 
     * That's why I use $s and $e
    */ 
    
    /**
     * @var DateTime
     */
    public $s;

    /**
     * @var DateTime
     */
    public $e;

    public function __construct( $start, $interval, $end ){
        $this->s = $start;
        $this->e = $end;
        
        parent::__construct( $start, $interval, $end );
    }

    public function fitsIn( DateTime $date ){
        return ( $this->s <= $date && $this->e >=$date );
    }

}

?>
