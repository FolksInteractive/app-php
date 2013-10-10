<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Enrollment extends Constraint {

    public $message = "The enrollment is invalid.";

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }
}

?>
