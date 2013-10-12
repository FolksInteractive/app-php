<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\PurchaseType;

/**
 * Order controller.
 *
 * @Route("/r/{idRelation}/orders")
 */
class OrderController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="client_relation_orders")
     * @Template("TCCoreBundle:Client:Relation/orders.html.twig")
     */
    public function ordersAction( $idRelation ) {

        $relation = $this->getRelationManager()->findClientRelation($idRelation);

        $order = $this->getOrderManager()->createOrder();
        $order->setRelation( $relation );

        $createForm = $this->createOrderForm( $order, $idRelation );
        
            
        if ( $this->getRequest()->getMethod() === "POST" ) {
            $createForm->handleRequest( $this->getRequest() );

            if ( $createForm->isValid() ) {
                $this->getOrderManager()->saveOrder($order);
                
               return $this->redirect( $this->generateUrl( 
                        'client_order_discuss', 
                        array(
                            'idOrder' => $order->getId(),
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
     * @Route("/progress", name="client_relation_progress")
     * @Template("TCCoreBundle:Client:Relation/progress.html.twig")
     */
    public function progressAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findClientRelation( $idRelation );

        return array(
            'relation' => $relation
        );
    }
    
    /**
     * Creates a new Order.
     *
     * @Route("/", name="client_order_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Client:Order/new.html.twig")
     */
    public function createAction( Request $request, $idRelation ) {
        $em = $this->getDoctrine()->getManager();

        $relation = $this->getRelationManager()->findClientRelation( $idRelation );

        $order = $this->getOrderManager()->createOrder();
        $order->setRelation( $relation );
        $form = $this->createOrderForm( $order, $idRelation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getOrderManager()->saveOrder($order);

            return $this->redirect( $this->generateUrl( 'client_order_show', array(
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
     * @Route("/new", name="client_order_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Order/new.html.twig")
     */
    public function newAction( $idRelation ) {
        $relation = $this->getRelationManager()->findClientRelation( $idRelation );
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
     * @Route("/{idOrder}", name="client_order_show")
     * @Template("TCCoreBundle:Client:Order/show.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findClientRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $idOrder );

        // For Client, if order is not open we redirect to a dumb relation page
        if ( !$order->isReady() ) {
            return $this->render( "TCCoreBundle:Client:Order/not_ready.html.twig", array(
                        'order' => $order,
                        'relation' => $relation)
            );
        }
                
        $purchaseForm = null;
        if( !$order->isApproved() ) {
            // Create order purchase form
            $purchaseForm = $this->createPurchaseForm( $order );

            // Handle purchase form
            if ( $request->getMethod() === "POST" ) {
                $purchaseForm->handleRequest( $request );

                if ( $purchaseForm->isValid() ) {
                    $this->getOrderManager()->purchaseOrder( $order );
                    $this->getOrderManager()->saveOrder( $order );
                    $purchaseForm = null;
                }
            }
        }
        
        return array(
            'order' => $order,
            'relation' => $relation,
            'purchaseForm' => ($purchaseForm) ? $purchaseForm->createView(): null
        );
    }   

    /**
     * Cancel an Order.
     *
     * @Route("/{idOrder}", name="vendor_order_cancel")
     * @Method("POST")
     */
    public function cancelAction( Request $request, $idOrder, $idRelation ) {

        $relation = $this->getRelationManager()->findVendorRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $idOrder );

        $form = $this->createCancelForm( $idOrder, $idRelation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            
        }

        return $this->redirect( $this->generateUrl( 'vendor_relation_orders', array("id" => $idRelation) ) );
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
     * Creates a form to purchase a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createPurchaseForm( Order $order ) {
        $form = $this->createForm( new PurchaseType(), $order, array(
            'action' => $this->generateUrl( 'client_order_show', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) ),
            'method' => 'POST',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Purchase') );
        return $form;
    }

    /**
     * Creates a form to cancel an order by id.
     *
     * @param mixed $idOrder The relation id
     *
     * @return Form The form
     */
    private function createCancelForm( $idOrder, $idRelation ) {
        return $this->createFormBuilder()
                        ->add( "active" )
                        ->setAction( $this->generateUrl( 'vendor_order_cancel', array('idOrder' => $idOrder, 'idRelation' => $idRelation) ) )
                        ->setMethod( 'POST' )
                        ->add( 'submit', 'submit', array('label' => 'Cancel Order') )
                        ->getForm()
        ;
    }
}
