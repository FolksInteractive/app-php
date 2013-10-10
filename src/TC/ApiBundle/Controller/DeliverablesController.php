<?php

namespace TC\ApiBundle\Controller;

use TC\ApiBundle\Model\OrderManager;
use TC\ApiBundle\Model\RelationManager;
use TC\ApiBundle\TransfertObject\DeliverableTO;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use InvalidArgumentException;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator;

/**
 * @RouteResource("Deliverable")
 */
class DeliverablesController extends Controller {

    public function cgetAction( $relation_id, $order_id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $order_id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $order->getDeliverables() );
        $view->setSerializationContext( $this->getContext( array("deliverable") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getAction( $relation_id, $order_id, $id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $order_id );
        $deliverable = $this->getOrderManager()->findDeliverable( $order, $id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $deliverable );
        $view->setSerializationContext( $this->getContext( array("deliverable") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function postAction( $relation_id, $order_id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $order_id );

        $deliverableTO = new DeliverableTO( $this->getRequest()->request->get( "deliverable" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $deliverableTO, array("create") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $deliverable = $this->getOrderManager()->createDeliverable( $order );

        if ( $deliverableTO->name )
            $deliverable->setName( $deliverableTO->name );

        if ( $deliverableTO->description )
            $deliverable->setDescription( $deliverableTO->description );

        if ( $deliverableTO->quantity !== null )
            $deliverable->setQuantity( $deliverableTO->quantity );

        if ( $deliverableTO->cost !== null )
            $deliverable->setCost( $deliverableTO->cost );
        
        $this->getOrderManager()->saveDeliverable( $deliverable );
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 201 );
        $view->setData( $deliverable );
        $view->setSerializationContext( $this->getContext( array("order") ) );

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    
    public function deleteAction($relation_id, $order_id, $id){
        
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $order_id );
        
        $deliverable = $this->getOrderManager()->findDeliverable( $order, $id );
        $this->getOrderManager()->removeDeliverable($deliverable);
        
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 204 );
        $view->setSerializationContext( $this->getContext( array("deliverable") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    
    public function putAction( $relation_id, $order_id, $id ) {
        $relation = $this->getRelationManager()->findRelation( $relation_id );
        $order = $this->getOrderManager()->findOrder( $relation, $order_id );

        $deliverableTO = new DeliverableTO( $this->getRequest()->request->get( "deliverable" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $deliverableTO, array("edit") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $deliverable = $this->getOrderManager()->findDeliverable( $order, $id );
        
        if ( $deliverableTO->name )
            $deliverable->setName( $deliverableTO->name );

        if ( $deliverableTO->description )
            $deliverable->setDescription( $deliverableTO->description );

        if ( $deliverableTO->quantity !== null )
            $deliverable->setQuantity( $deliverableTO->quantity );

        if ( $deliverableTO->cost !== null )
            $deliverable->setCost( $deliverableTO->cost );
        
        $this->getOrderManager()->saveDeliverable( $deliverable );
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $deliverable );
        $view->setSerializationContext( $this->getContext( array("order") ) );

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

    private function getContext( $groups ) {
        $context = new SerializationContext();
        $context->setVersion( "0" );
        $context->setGroups( $groups );

        return $context;
    }

}

?>
