<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Relation extends Constraint {

    public $message = 'The relation creation is invalid';

    public function validatedBy() {
        return 'tc_validator_relation';
    }

    public function getTargets() {
        return self::CLASS_CONSTRAINT;
    }

}

?>
