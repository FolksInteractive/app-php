<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class EmailCollection extends Constraint {

    public $message = 'Incorrect email(s) : %emails%';

    public function validatedBy() {
        return 'tc_validator_email_collection';
    }

    public function getTargets() {
        return self::PROPERTY_CONSTRAINT;
    }

}

?>
