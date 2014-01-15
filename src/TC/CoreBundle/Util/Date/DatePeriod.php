<?php

namespace TC\CoreBundle\Util\Date;

use DatePeriod as BaseDatePeriod;
use DateTime;

class DatePeriod extends BaseDatePeriod {

    /**
     * @var DateTime
     */
    public $start;

    /**
     * @var DateTime
     */
    public $end;

    public function __construct( $start, $interval, $end ){
        $this->start = $start;
        $this->end = $end;
        
        parent::__construct( $start, $interval, $end );
    }

    public function fitsIn( DateTime $date ){
        return ( $this->start <= $date && $this->end >=$date );
    }

}

?>
