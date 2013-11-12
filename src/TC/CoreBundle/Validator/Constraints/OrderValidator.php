<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

class OrderValidator extends ConstraintValidator {

    public function validate($order, Constraint $constraint){
        //$this->context->addViolation($constraint->message);
    }

}

?>
