<?php
namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Doctrine\Common\Collections\Collection;

class NotBlankButNullableValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /// If Blank ...
        if (false === $value || (empty($value) && '0' != $value)) {
            
            if ($value != null){
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
?>
