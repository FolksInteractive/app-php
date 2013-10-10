<?php

namespace TC\ApiBundle\Controller;

use TC\ApiBundle\Entity\Order;
use TC\ApiBundle\Form\CommentType;
use TC\ApiBundle\Form\OrderType;
use TC\ApiBundle\Model\OrderManager;
use TC\ApiBundle\Model\RelationManager;
use TC\ApiBundle\TransfertObject\OrderTO;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use InvalidArgumentException;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator;
/**
 * 
 * @RouteResource("Order")
 */
class OrdersController extends Controller {

    public function cgetAction( $relation_id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $orders = $this->getOrderManager()->getOrders( $relation );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $orders );        
        $view->setSerializationContext( $this->getContext( array("order") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getAction( $relation_id, $id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $order );
        $view->setSerializationContext( $this->getContext( array("order") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function postAction( $relation_id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        
        $orderTO = new OrderTO( $this->getRequest()->request->get( "order" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $orderTO, array("create") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $order = $this->getOrderManager()->createOrder($relation);

        if ( $orderTO->request )
            $order->setRequest ( $orderTO->request );
        
        if ( $orderTO->offer )
            $order->setOffer( $orderTO->offer );


        $this->getOrderManager()->saveOrder( $order );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 201 );
        $view->setData( $order );
        $view->setSerializationContext( $this->getContext( array("order") ) );

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function putAction( $relation_id, $id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        
        $orderTO = new OrderTO( $this->getRequest()->request->get( "order" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $orderTO, array("edit") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $order = $this->getOrderManager()->findOrder($relation, $id);

        if ( $orderTO->offer )
            $order->setOffer( $orderTO->offer );

        // Create and add deliverables
        if ( $orderTO->deliverables ) {
            break;
            foreach ( $orderTO->deliverables as $key => $value ) {
                $deliverable = $this->getOrderManager()->createDeliverable( $order );
            }
        }

        $this->getOrderManager()->saveOrder( $order );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 201 );
        $view->setData( $order );
        $view->setSerializationContext( $this->getContext( array("order") ) );

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    public function postCompleteAction ( $relation_id, $id){
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        $this->getOrderManager()->completeOrder( $order );
        
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $order );
        $view->setSerializationContext( $this->getContext( array("order") ) ); 
        
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    public function postApproveAction ( $relation_id, $id){
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        $this->getOrderManager()->approveOrder( $order );
        
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $order );
        $view->setSerializationContext( $this->getContext( array("order") ) );
        
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    public function postCommentAction( $relation_id, $id){
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $id );
        $comment = $this->getOrderManager()->createComment( $order );

        $statusCode = $order->getId() ? 200 : 201;
        
        $form = $this->createForm( new CommentType(), $comment );
        $form->handleRequest( $this->getRequest() );

        /* @var $view View */
        $view = View::create();
        if ( $form->isValid() ) {
            $this->getOrderManager()->saveComment( $comment );
            
            $view->setStatusCode( $statusCode );
            $view->setData($comment);
            $view->setSerializationContext( $this->getContext( array("order") ) );
        } else {
            $view->setData( $form );
            $view->setStatusCode( 400 );
        }

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    
    /**
     * @return RelationManager
     */
    private function getRelationManager() {
        return $this->container->get( "tc.manager.relation" );
    }

    /**
     * @return OrderManager
     */
    private function getOrderManager() {
        return $this->container->get( "tc.manager.order" );
    }
    
    /**
     * @return Validator
     */
    private function getValidator() {
        return $this->get( 'validator' );
    }
    
    private function getContext( $groups ){
        $context = new SerializationContext();
        $context->setVersion("0");
        $context->setGroups($groups);
        
        return $context;
    }
}

?>
