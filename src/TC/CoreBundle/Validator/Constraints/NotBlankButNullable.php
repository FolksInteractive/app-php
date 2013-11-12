<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotBlankButNullable extends Constraint {

    public $message = 'This value cannot be empty.';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}

?>
