<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Bill;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

/**
 * RelationManager
 *
 * @author Francis Poirier
 */
class BillManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var RelationManager $wm 
     */
    protected $rm;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var Workspace $workspace
     */
    protected $workspace;

    /**
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * @var Validator $validator
     */
    protected $validator;

    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct( EntityManager $em, RelationManager $rm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->rm = $rm;
        $this->validator = $validator;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return Bill
     */
    public function getOpenBill( Relation $relation ){
        return $relation->getOpenBill();
    }
    
    
    /**
     * 
     * @param Relation $relation
     * @return Bill
     */
    public function getClosedBills( Relation $relation ){
        return $relation->getClosedBills();
    }
    /**
     * 
     * @param Relation $relation
     * @throws AccessDeniedException
     */
    public function closeBill( Relation $relation ) {
        if ( $relation->getVendor() == $this->workspace ) {
            // Closing bill
            $openBill = $relation->getOpenBill();

            if ( $openBill->getDeliverables()->count() < 1 )
                return;

            $openBill->setClosed( true );
            $relation->addClosedBill( $openBill );

            // Opening a new bill
            $newOpenBill = new Bill();
            $newOpenBill->setRelation( $relation );
            $relation->setOpenBill( $newOpenBill );

            $this->rm->save( $relation );
        }else {
            throw new AccessDeniedException( "You must be the vendor of the relation to close the bill." );
        }
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return Bill
     * @throws NotFoundHttpException
     */
    public function findClosedByRelation( Relation $relation, $id ) {
        foreach ( $relation->getClosedBills() as $bill ) {
            if ( $bill->getId() == $id )
                return $bill;
        }

        throw new NotFoundHttpException( 'Invoice not found' );
    }

}

?>
