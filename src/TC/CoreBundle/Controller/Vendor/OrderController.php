<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\OrderType;

/**
 * Order controller.
 *
 * @Route("/r/{idRelation}/orders")
 */
class OrderController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="vendor_relation_orders")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_orders_vendor.html.twig")
     */
    public function ordersAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $orders = $this->getOrderManager()->findAllForVendor($relation);
        
        return array(
            'relation' => $relation,
            'orders' => $orders
        );
    }
    
    /**
     * Displays a form to edit an existing Order.
     *
     * @Route("/new/{idRFP}", name="vendor_order_new", defaults={"idOrder"=null})
     * @Route("/{idOrder}/edit", name="vendor_order_edit")
     * @Method("GET")
     * @Template("TCCoreBundle:Order:order_edit_vendor.html.twig")
     */
    public function editAction( $idRelation, $idOrder = null, $idRFP = null ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        
        // Check wether it is a new or an existing order
        if( $idOrder != null){
            $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );
        }else{            
            $order = $this->getOrderManager()->create( $relation );
            
            if( $idRFP ){
                $rfp = $this->getRFPManager()->findByRelation($relation, $idRFP);
                $order->setRFP($rfp);
            }
        }
        
        $form = $this->createOrderForm( $order );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return array(
            'order' => $order,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }

    /**
     * Edits an existing Order.
     *
     * @Route("/", name="vendor_order_create")
     * @Route("/{idOrder}/edit", name="vendor_order_update", defaults={"idOrder"=null})
     * @Method({"POST", "PUT"})
     * @Template("TCCoreBundle:Order:order_edit_vendor.html.twig")
     */
    public function updateAction( Request $request, $idRelation, $idOrder = null ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        
        if( $idOrder != null){
            $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );
        }else{            
            $order = $this->getOrderManager()->create( $relation );
        }
        /*
         * http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-tags-to-be-removed
         */
        $originalDeliverables = array();
        // Create an array of the current Deliverables in the database
        foreach ( $order->getDeliverables() as $deliverable ) {
            $originalDeliverables[] = $deliverable;
        }

        $form = $this->createOrderForm( $order );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            
            // filter $originalDeliverables to contain deliverables no longer present
            foreach ( $order->getDeliverables() as $deliverable ) {
                foreach ( $originalDeliverables as $key => $toDel ) {
                    if ( $toDel->getId() === $deliverable->getId() ) {
                        unset( $originalDeliverables[$key] );
                    }
                }
                // Set the deliverable creator for the new ones. 
                // Habitually, this is done when creating the deliverable 
                // via the OrderManager->createDeliverable( $order ) but 
                // those deliverable are created by doctrine and I can't 
                // figure out how to specify Symfony to use the manager
                if ( $deliverable->getId() == null ) 
                    $deliverable->setCreator( $this->getWorkspace() );
            }

            // remove the relationship between the order and the deliverable
            foreach ( $originalDeliverables as $deliverable ) {                
                $this->getDeliverableManager()->remove($deliverable);
            }            
            
            if( $form->get('save_as_ready')->isClicked())
                $this->getOrderManager()->ready( $order );
            
            $this->getOrderManager()->save($order);

            return $this->redirect( $this->generateUrl( 'vendor_order_show', array('idRelation' => $idRelation, 'idOrder' => $order->getId()) ) );
        }

        return array(
            'order' => $order,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }

    /**
     * Finds and displays a Order.
     *
     * @Route("/{idOrder}", name="vendor_order_show")
     * @Template("TCCoreBundle:Order:order_show_vendor.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );

        return array(
            'order' => $order,
            'relation' => $relation
        );
    }

    /**
     * Cancel a Order.
     *
     * @Route("/{idOrder}/cancel", name="vendor_order_cancel")
     * @Template("TCCoreBundle:Order:order_cancel_vendor.html.twig")
     */
    public function cancelAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );

        // If Order is already cancelled redirect to orders list
        if($order->getCancelled())
            return $this->redirect( $this->generateUrl( 'vendor_relation_orders', array('idRelation' => $idRelation) ) );

        $form = $this->createCancelForm( $order );

        if ( $request->getMethod() === "PUT" ){
            
            $form->handleRequest( $request );
            
            if($form->isValid() ) {
                $cancellation = $form->getData();
                $this->getOrderManager()->cancel( $order, $cancellation );
                $this->getOrderManager()->save( $order );

                return $this->redirect( $this->generateUrl( 'vendor_relation_orders', array('idRelation' => $idRelation) ) );
            }
        }

        return array(
            'form' => $form->createView(),
            'order' => $order,
            'relation' => $relation
        );
    }
    
    /**
     * Reopen a Order.
     *
     * @Route("/{idOrder}/reopen", name="vendor_order_reopen")
     */
    public function reopenAction( $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );
        
        $this->getOrderManager()->reopen( $order );
        $this->getOrderManager()->save( $order );
        
        return $this->redirect( $this->generateUrl( 'vendor_order_show', array('idRelation' => $idRelation, 'idOrder' => $idOrder) ) );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
        
    /**
     * Creates a form to edit a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createOrderForm( Order $order ) {
        
        if( !$order->getId() ){
            $action = $this->generateUrl( 'vendor_order_create', array('idRelation' => $order->getRelation()->getId()) );
        }else{
            $action = $this->generateUrl( 'vendor_order_update', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) );
        }
        
        $rfps = $this->getRFPManager()->findAllUnproposedByRelation($order->getRelation());
        
        // Append the already refered RFP because it won't be returned by the 
        // manager and we need into the list
        if( $order->getRfp() !== null )
            $rfps[] = ($order->getRfp());
        
        $form = $this->createForm( new OrderType(), $order, array(
            'action' => $action,
            'method' => 'PUT',
            'rfps' => $rfps
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Update') );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return $form;
    }

    /**
     * Creates a form to cancel a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createCancelForm( Order $order ) {
        $action = $this->generateUrl( 'vendor_order_cancel', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) );
        
        $builder = $this->createFormBuilder( null , array(
                    'action' => $action,
                    'method' => 'PUT',
                ) );
                
        if( $order->getReady() ){
            $builder->add( 'why', 'choice', array(
                "choices" => array(
                    "It doesn't apply anymore.",
                    "It is too late now.",
                    "There was a misunderstanding in the requierement or clause."
                ),
                'expanded' => true,
            ) )

            ->add( 'other', 'textarea', array( "required" => false ) );
        }
        
        $builder->add( 'submit', 'submit' );

        return $builder->getForm();
    }

}
