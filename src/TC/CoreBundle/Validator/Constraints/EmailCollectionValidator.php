<?php

namespace TC\CoreBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Validator;
use Symfony\Component\Validator\Constraints\Email as EmailConstraint;

class EmailCollectionValidator extends ConstraintValidator {

    protected $validator;

    public function __construct( Validator $validator) {
        $this->validator = $validator;
    }

    public function validate($emails, Constraint $constraint){
        $emailConstraint = new EmailConstraint();
        $invalidEmails = array();
        
        // Validates each email and store the wrong ones
        foreach( $emails as $email){
            if( $this->validator->validateValue($email, $emailConstraint)->count()>0 )
                    $invalidEmails[] = $email;
        }
        
        
        if( count($invalidEmails)> 0 )
            $this->context->addViolation($constraint->message, array('%emails%' => implode(",", $invalidEmails) ) );
            
    }

}

?>
