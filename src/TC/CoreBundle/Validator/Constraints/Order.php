<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Order extends Constraint {

    public $message = 'The order is invalid';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}

?>
