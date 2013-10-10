<?php
namespace TC\CoreBundle\Validator\Constraints;

use TC\CoreBundle\Entity\Enrollment as EnrollmentEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EnrollmentValidator extends ConstraintValidator
{
    /**
     * 
     * @param EnrollmentEntity $enrollment
     * @param Constraint $constraint
     */
    public function validate($enrollment, Constraint $constraint)
    {        
        //$relation = $enrollment->getRelation();        
    }
}
?>
