<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Bill;
use TC\CoreBundle\Entity\Deliverable;
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
     * @return ArrayCollection
     */
    public function findAllByRelation( Relation $relation ){
        return $relation->getBills();
    }
    /**
     * 
     * @param Relation $relation
     * @throws AccessDeniedException
     */
    public function close( Bill $openBill ) {
        $relation = $openBill->getRelation();
        
        if ( $relation->getVendor() == $this->workspace ) {
            
            if ( $openBill->getClosed() )
                return;
            
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
    public function findByRelation( Relation $relation, $id ) {
        try {
            $bill = $this->em->getRepository( "TCCoreBundle:Bill" )
                    ->createQueryBuilder( "b" )
                    ->where( "b.relation = :relation" )
                    ->andWhere( "b.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Invoice not found' );
        }
        
        return $bill;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return Bill
     */
    public function create( Relation $relation ) {
        $bill = new Bill();
        $bill->setRelation($relation);
        
        return $bill;
    }
    
    public function addDeliverable( Bill $bill, Deliverable $deliverable ){
        $deliverable->setBilled(true);
        $bill->addDeliverable($deliverable);
        
        return $bill;
    }

    /**
     * 
     * @param Bill $bill
     */
    public function save( Bill $bill ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $bill );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $bill );
        $this->em->flush();
    }

    /**
     * 
     * @param Bill $bill
     */
    public function remove( Bill $bill ) {
        $this->em->remove( $bill );
        $this->em->flush();
    }
}

?>
