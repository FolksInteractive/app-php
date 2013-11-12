<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Form\RelationProgressType;

/**
 * Order controller.
 *
 * @Route("/r/{idRelation}/progress")
 */
class ProgressController extends BaseController {            
    
    /**
     * Displays the work in progress of a relation
     *
     * @Route("/", name="vendor_relation_progress")
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
    private function createProgressForm( Relation $relation ) {
        $deliverablesTodo = array();
        
        foreach( $relation->getOrdersTodo() as $orderKey=>$order){
            foreach( $order->getDeliverablesTodo() as $deliverableKey=>$deliverable){
                $deliverablesTodo[] = $deliverable;
            }    
        }
        
        
        $form = $this->createForm(
                new RelationProgressType(), 
                null, 
                array(
                    'action' => $this->generateUrl( 'vendor_relation_progress', array('idRelation' => $relation->getId()) ),
                    'method' => 'POST',
                ));

        $form->add( 'submit', 'submit', array('label' => 'Save') );

        
        return $form;
    }

}
