<?php

namespace TC\CoreBundle\Util\Date;

use DatePeriod as BaseDatePeriod;
use DateTime;

class DatePeriod extends BaseDatePeriod {
    
    /* 
     * Classes inherited from DatePeriod class cannot use their declared properties
     * https://bugs.php.net/bug.php?id=65672
     * 
     * That's why I added _ befor variable $start and $end
    */ 
    
    /**
     * @var DateTime
     */
    public $_start;

    /**
     * @var DateTime
     */
    public $_end;

    public function __construct( $start, $interval, $end ){
        $this->_start = $start;
        $this->_end = $end;
        
        parent::__construct( $start, $interval, $end );
    }

    public function fitsIn( DateTime $date ){
        return ( $this->_start <= $date && $this->_end >=$date );
    }

}

?>
