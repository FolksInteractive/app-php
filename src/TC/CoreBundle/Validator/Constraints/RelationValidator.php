<?php

namespace TC\CoreBundle\Validator\Constraints;

use Doctrine\ORM\EntityManager;
use TC\CoreBundle\Entity\Enrollment as EnrollmentEntity;
use TC\CoreBundle\Entity\Relation as RelationEntity;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class RelationValidator extends ConstraintValidator {

    protected $em;

    public function __construct( EntityManager $em ) {
        $this->em = $em;
    }

    /**
     * 
     * @param RelationEntity $relation
     * @param Constraint $constraint
     */
    public function validate( $relation, Constraint $constraint ) {

        // The creator of the relation must either be the client or the vendor
        if ( $relation->getCreator() && $relation->getCreator() != $relation->getVendor() && $relation->getCreator() != $relation->getClient() )
            $this->context->addViolation( "The creator of the new relation must be part of it." );
                
        // The vendor and the client cannot be the same person
        if ( $relation->getVendorEnrollment() && $relation->getClientEnrollment() && $relation->getVendorEnrollment()->getEmail() ==  $relation->getClientEnrollment()->getEmail() )
            $this->context->addViolation( "The vendor and the client cannot be the same person." );

  }

}

?>
