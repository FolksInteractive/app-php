<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\OrderEditType;

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
     * @Template("TCCoreBundle:Vendor:Relation/orders.html.twig")
     */
    public function ordersAction( $idRelation ) {

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );

        $order = $this->getOrderManager()->createOrder();
        $order->setRelation( $relation );

        $createForm = $this->createOrderForm( $order, $idRelation );

        if ( $this->getRequest()->getMethod() === "POST" ) {
            $createForm->handleRequest( $this->getRequest() );

            if ( $createForm->isValid() ) {
                $this->getOrderManager()->saveOrder( $order );

                return $this->redirect( $this->generateUrl(
                                        'vendor_order_discuss', array(
                                    'id' => $order->getId(),
                                    'idRelation' => $idRelation)
                                )
                );
            }
        }

        return array(
            'relation' => $relation,
            'create_form' => $createForm->createView(),
        );
    }
            
    /**
     * Displays the work in progress of a relation
     *
     * @Route("/progress", name="vendor_relation_progress")
     * @Template("TCCoreBundle:Vendor:Relation/progress.html.twig")
     */
    public function progressAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );

        $form = $this->createProgressForm( $relation );
        
        if($request->getMethod() === "POST"){
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                foreach($data["deliverables"] as $key=>$deliverable){
                    $this->getOrderManager()->completeDeliverable($deliverable);
                    $this->getOrderManager()->saveDeliverable($deliverable);
                }
            }
        }
        return array(
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a new Order.
     *
     * @Route("/", name="vendor_order_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Vendor:Order/new.html.twig")
     */
    public function createAction( Request $request, $idRelation ) {
        $em = $this->getDoctrine()->getManager();

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );

        $order = $this->getOrderManager()->createOrder();
        $order->setRelation( $relation );
        $form = $this->createOrderForm( $order, $idRelation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getOrderManager()->saveOrder( $order );

            return $this->redirect( $this->generateUrl( 'vendor_order_show', array(
                                'idOrder' => $order->getId(),
                                'idRelation' => $idRelation) )
            );
        }

        return array(
            'order' => $order,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }

    /**
     * Displays a form to create a new Order.
     *
     * @Route("/new", name="vendor_order_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Vendor:Order/new.html.twig")
     */
    public function newAction( $idRelation ) {
        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );
        $order = $this->getOrderManager()->createOrder();
        $order->setRelation( $relation );

        $form = $this->createOrderForm( $order, $idRelation );

        return array(
            'order' => $order,
            'form' => $form->createView(),
            'relation' => $relation
        );
    }

    /**
     * Finds and displays a Order.
     *
     * @Route("/{idOrder}", name="vendor_order_show")
     * @Template("TCCoreBundle:Vendor:Order/show.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $idOrder );

        // For Vendor, if order is not open we redirect to a dumb relation page
        if ( !$order->isOpen() ) {
            return $this->render( "TCCoreBundle:Vendor:Order/not_ready.html.twig", array(
                        'order' => $order,
                        'relation' => $relation)
            );
        }

        return array(
            'order' => $order,
            'relation' => $relation
        );
    }
    
    /**
     * Displays a form to edit an existing Order.
     *
     * @Route("/{idOrder}/edit", name="vendor_order_edit")
     * @Method("GET")
     * @Template("TCCoreBundle:Vendor:Order/edit.html.twig")
     */
    public function editAction( $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $idOrder );

        $form = $this->createEditForm( $order );
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
     * @Route("/{idOrder}/edit", name="vendor_order_update")
     * @Method("PUT")
     * @Template("TCCoreBundle:Vendor:Order/edit.html.twig")
     */
    public function updateAction( Request $request, $idRelation, $idOrder ) {
        $em = $this->getDoctrine()->getManager();

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $idOrder );

        /*
         * http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-tags-to-be-removed
         */
        $originalDeliverables = array();
        // Create an array of the current Deliverables in the database
        foreach ( $order->getDeliverables() as $deliverable ) {
            $originalDeliverables[] = $deliverable;
        }

        $form = $this->createEditForm( $order );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            if( $form->get('save_as_ready')->isClicked())
                $this->getOrderManager()->readyOrder( $order );
            
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
                // remove the Deliverable from the Order
                $order->removeDeliverable( $deliverable );

                $em->remove( $deliverable );
                $em->flush();
            }
            
            $this->getOrderManager()->saveOrder($order);

            return $this->redirect( $this->generateUrl( 'vendor_order_show', array('idRelation' => $idRelation, 'idOrder' => $idOrder) ) );
        }

        return array(
            'order' => $order,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * Creates a form to create a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    protected function createOrderForm( $order, $idRelation ){
        $form = parent::createOrderForm($order, $idRelation);
        
        return $form;
    }
    
    /**
     * Creates a form to edit a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createEditForm( Order $order ) {
        $form = $this->createForm( new OrderEditType(), $order, array(
            'action' => $this->generateUrl( 'vendor_order_update', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) ),
            'method' => 'PUT',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Update') );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return $form;
    }

}
