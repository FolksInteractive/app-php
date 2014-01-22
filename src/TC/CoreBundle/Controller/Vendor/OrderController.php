<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\OrderDeclinalType;
use TC\CoreBundle\Form\PurchaseType;

/**
 * Order controller.
 *
 * @Route("/vendors/{idRelation}/orders")
 */
class OrderController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="vendor_orders")
     * @Template("TCCoreBundle:Relation:orders_vendor.html.twig")
     */
    public function ordersAction( $idRelation ) {

        $relation = $this->getRelationManager()->findVendor($idRelation);

        $orders = $this->getOrderManager()->findAllByVendor($relation);
                
        return array(
            'relation' => $relation,
            'orders' => $orders
        );
    }

    /**
     * Finds and displays a Order.
     *
     * @Route("/{idOrder}", name="vendor_order_show")
     * @Template("TCCoreBundle:Order:order_show_vendor.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );        
                        
        $purchaseForm = null;
        if( $this->getOrderManager()->isPurchasable($order) ) {
            // Create order purchase form
            $purchaseForm = $this->createPurchaseForm( $order );

            // Handle purchase form
            if ( $request->getMethod() === "POST" ) {
                $purchaseForm->handleRequest( $request );

                if ( $purchaseForm->isValid() ) {
                    $this->getOrderManager()->purchase( $order );
                    $this->getOrderManager()->save( $order );
                    $purchaseForm = null;
                    
                    return $this->redirect( $this->generateUrl( 'vendor_progress', array('idRelation' => $idRelation) ) );
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
     * Reopen a Order.
     *
     * @Route("/{idOrder}/decline", name="vendor_order_decline")
     * @Template("TCCoreBundle:Order:order_decline_vendor.html.twig")
     */
    public function declineAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );

        // If Order is already declined redirect to orders list
        if($order->getDeclined())
            return $this->redirect( $this->generateUrl( 'vendor_orders', array('idRelation' => $idRelation) ) );

        $form = $this->createDeclineForm( $order );

        if ( $request->getMethod() === "PUT" ){
            
            $form->handleRequest( $request );
            
            if($form->isValid() ) {
                $refusal = $form->getData();
                $this->getOrderManager()->decline( $order, $refusal );
                $this->getOrderManager()->save( $order );

                return $this->redirect( $this->generateUrl( 'vendor_orders', array('idRelation' => $idRelation) ) );
            }
        }

        return array(
            'form' => $form->createView(),
            'order' => $order,
            'relation' => $relation
        );
    }
    
    
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */    

    /**
     * Creates a form to purchase a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createPurchaseForm( Order $order ) {
        $form = $this->createForm( new PurchaseType(), $order, array(
            'action' => $this->generateUrl( 'vendor_order_show', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) ),
            'method' => 'POST',
            ) );
        
        $form->add( 'submit', 'submit', array('label' => 'Purchase') );
        
        return $form;
    }

    /**
     * Creates a form to decline a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    private function createDeclineForm( Order $order ) {
        $action = $this->generateUrl( 'vendor_order_decline', array('idRelation' => $order->getRelation()->getId(), 'idOrder' => $order->getId()) );
              
        $form = $this->createForm( new OrderDeclinalType(), null, array(
            'action' => $action,
            'method' => 'PUT',
        ) );
        
        $form->add( 'submit', 'submit' );
        
        return $form;
    }
}
