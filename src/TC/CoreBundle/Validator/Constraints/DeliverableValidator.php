<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;

class DeliverableValidator extends ConstraintValidator {

    public function validate($deliverable, Constraint $constraint){
        
        // If the deliverable is set to completed but the order is not approved yet
        if($deliverable->isCompleted() && !$deliverable->getOrder()->isApproved()){
            $this->context->addViolation("A Deliverable can be completed only when its Order is purchased.");
        }
    }

}

?>
