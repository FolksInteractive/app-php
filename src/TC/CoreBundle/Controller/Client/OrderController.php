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
     * @Template("TCCoreBundle:Relation:relation_orders_client.html.twig")
     */
    public function ordersAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByClient($idRelation);

        return array(
            'relation' => $relation
        );
    }

    /**
     * Finds and displays a Order.
     *
     * @Route("/{idOrder}", name="client_order_show")
     * @Template("TCCoreBundle:Order:order_show_client.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idOrder ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );        
                        
        $purchaseForm = null;
        if( !$order->isApproved() ) {
            // Create order purchase form
            $purchaseForm = $this->createPurchaseForm( $order );

            // Handle purchase form
            if ( $request->getMethod() === "POST" ) {
                $purchaseForm->handleRequest( $request );

                if ( $purchaseForm->isValid() ) {
                    $this->getOrderManager()->purchase( $order );
                    $this->getOrderManager()->save( $order );
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

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        $order = $this->getOrderManager()->findByRelation( $relation, $idOrder );

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
