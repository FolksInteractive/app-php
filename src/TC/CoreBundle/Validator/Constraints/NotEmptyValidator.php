<?php
namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;

class NotEmptyValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if($value->isEmpty()){
            $this->context->addViolation( $constraint->message );
        }
    }
}
?>
