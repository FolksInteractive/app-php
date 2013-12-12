<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;

/**
 * OrderManager
 *
 * @author Francis Poirier
 */
class OrderManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var Workspace $workspace
     */
    protected $workspace;

    /**
     *
     * @var RelationManager $pm
     */
    protected $pm;

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
    public function __construct( EntityManager $em, RelationManager $pm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->pm = $pm;
        $this->validator = $validator;
    }

    /**
     * 
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllByRelation( Relation $relation ) {
        return $relation->getOrders();
    }

    /**
     * 
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllForVendor( Relation $relation ) {
        $orders = $this->em->getRepository( "TCCoreBundle:Order" )
                ->createQueryBuilder( "o" )
                ->where( "o.relation = :relation" )
                ->setParameter( "relation", $relation )
                ->getQuery()
                ->getResult();
        
        return $orders;
    }    

    /**
     * 
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllForClient( Relation $relation ) {
        $orders = $this->em->getRepository( "TCCoreBundle:Order" )
                ->createQueryBuilder( "o" )
                ->where( "o.relation = :relation" )
                ->andWhere( "o.ready = true" )
                ->setParameter( "relation", $relation )
                ->getQuery()
                ->getResult();
        
        return $orders;
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return Order
     * @throws NotFoundHttpException
     */
    public function findByRelation( $relation, $id ) {
        try {
            /**
             *  @var Order $order 
             */
            $order = $this->em->getRepository( "TCCoreBundle:Order" )
                    ->createQueryBuilder( "o" )
                    ->where( "o.relation = :relation" )
                    ->andWhere( "o.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Proposal not found' );
        }
        
        // If the order is not ready the client can't read the order
        if( $this->workspace == $order->getRelation()->getClient() && !$order->isReady())
            throw new NotFoundHttpException( 'Proposal not found' );
            
        return $order;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return Order
     */
    public function create( Relation $relation ) {
        $order = new Order();
        $order->setCreator( $this->workspace );
        $order->setRelation($relation);
        
        $thread = new Thread();
        $thread->addFollower( $order->getRelation()->getVendor());
        $thread->addFollower( $order->getRelation()->getClient());
        $order->setThread( $thread );
        

        /**
         * @todo Send notification
         */
        return $order;
    }

    /**
     * 
     * @param Order $order
     * @throws AccessDeniedHttpException
     */
    public function complete( Order $order ) {

        if ( $order->getRelation()->getVendor() == $this->workspace ) {

            $order->setCompleted( true );
            $this->save( $order );
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function ready( Order $order ) {
        
        if ( $order->getRelation()->getVendor() == $this->workspace ) {
            $order->setReady(true);
            $this->mailer->sendOrderReadyNotification( $order );
        }
    }
    /**
     * 
     * @param Order $order
     * @throws AccessDeniedHttpException
     */
    public function purchase( Order $order ) {
        if( $order->isApproved() )
            return;
        
        // If User is the client and the order has an offer and a value
        if ( $order->getRelation()->getClient() == $this->workspace ) {

            $order->setApproved( true );
            $this->save( $order );
            
            $this->mailer->sendOrderPurchaseNotification( $order );
            
        } else {
            throw new AccessDeniedHttpException();
        }
    }
    
    /**
     * 
     * @param Order $order
     * @param object $cancellation See Client/OrderController::createCancelForm
     */
    public function cancel( Order $order, $cancellation = null ) {
        
        if ( $order->getRelation()->getVendor() == $this->workspace ) {
            $order->setCancelled(true);
            
            if($order->getReady())
                $this->mailer->sendOrderCancellation($order, $cancellation);
        }
    }
    
    /**
     * 
     * @param Order $order
     * @param object $refusal See Vendor/OrderController::createRefuseForm
     */
    public function refuse( Order $order, $refusal = null ) {
        
        if ( $order->getRelation()->getClient() == $this->workspace ) {
            $order->setRefused(true);
            
            $this->mailer->sendOrderRefusal($order, $refusal);
        }
    }

    /**
     * 
     * @param Order $order
     */
    public function save( Order $order ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $order );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $order );
        $this->em->flush();
    }

    /**
     * 
     * @param Order $order
     */
    public function remove( Order $order ) {
        $this->em->remove( $order );
        $this->em->flush();
    }
}

?>
