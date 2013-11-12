<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class NotEmpty extends Constraint {

    public $message = 'The collection cannot be empty.';

    public function validatedBy() {
        return get_class($this) . 'Validator';
    }

}

?>
