<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use TC\CoreBundle\Entity\Comment;
use TC\CoreBundle\Entity\Deliverable;
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
    public function getOrders( Relation $relation ) {
        return $relation->getOrders();
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return Order
     * @throws NotFoundHttpException
     */
    public function findOrder( $relation, $id ) {
        try {
            /* @var $relation Order */
            $order = $this->em->getRepository( "TCCoreBundle:Order" )
                    ->createQueryBuilder( "o" )
                    ->where( "o.relation = :relation" )
                    ->andWhere( "o.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Order not found' );
        }
        return $order;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return Order
     */
    public function createOrder( Relation $relation ) {
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
    public function completeOrder( Order $order ) {

        if ( $order->getRelation()->getVendor() == $this->workspace ) {

            $order->setCompleted( true );
            $this->saveOrder( $order );
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    public function readyOrder( Order $order ) {
        
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
    public function purchaseOrder( Order $order ) {
        if( $order->isApproved() )
            return;
        
        // If User is the client and the order has an offer and a value
        if ( $order->getRelation()->getClient() == $this->workspace &&
                $order->getOffer() != null &&
                $order->getTotal() != null ) {

            $order->setApproved( true );
            $this->saveOrder( $order );
            
            $this->mailer->sendOrderPurchaseNotification( $order );
            
        } else {
            throw new AccessDeniedHttpException();
        }
    }

    /**
     * 
     * @param Order $order
     */
    public function saveOrder( Order $order ) {
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
    public function removeOrder( Order $order ) {
        $this->em->remove( $order );
        $this->em->flush();
    }

    /**
     * 
     * @param Order $order
     * @return Deliverable
     */
    public function createDeliverable( Order $order ) {
        $deliverable = new Deliverable();
        $deliverable->setOrder( $order );
        $deliverable->setCreator( $this->workspace );

        /**
         * @todo Send notification
         */
        return $deliverable;
    }

    /**
     * 
     * @param Order $order
     * @param integer $id
     * @return Deliverable
     * @throws NotFoundHttpException
     */
    public function findDeliverable( Order $order, $id ) {
        try {
            /* @var $relation Order */
            $deliverable = $this->em->getRepository( "TCCoreBundle:Deliverable" )
                    ->createQueryBuilder( "d" )
                    ->where( "d.order = :order" )
                    ->andWhere( "d.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "order", $order )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Deliverable not found' );
        }
        return $deliverable;
    }
    
    public function completeDeliverable(Deliverable $deliverable){
        // Do nothing if already completed
        if($deliverable->isCompleted())
            return;
        
        //Only vendor can set a Deliverable to completed
        if($this->workspace != $deliverable->getOrder()->getRelation()->getVendor())
            throw new AccessDeniedHttpException();
        
        $deliverable->setCompleted(true);
        $deliverable->getOrder()->getRelation()->getOpenBill()->addDeliverable($deliverable);
    }

    /**
     * 
     * @param Deliverable $deliverable
     */
    public function saveDeliverable( Deliverable $deliverable ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $deliverable );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $deliverable );
        $this->em->flush();
    }

    /**
     * 
     * @param Deliverable $order
     */
    public function removeDeliverable( Deliverable $deliverable ) {
        $this->em->remove( $deliverable );
        $this->em->flush();
    }
}

?>
