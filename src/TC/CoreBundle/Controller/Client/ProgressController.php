<?php

namespace TC\CoreBundle\Controller\Client;

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
 * @Route("/clients/{idRelation}/monitoring")
 */
class ProgressController extends BaseController {            
    
    /**
     * Displays the work in progress of a relation
     *
     * @Route("/", name="client_progress")
     * @Template("TCCoreBundle:Relation:progress_client.html.twig")
     */
    public function progressAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findClient( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllInProgressByRelation($relation);
        
        $form = $this->createProgressForm( $relation, $deliverables );
        
        if($request->getMethod() === "POST"){
            $form->handleRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                
                $hasCompletion = false;
                
                foreach($data['deliverables'] as $deliverable){
                    if( $deliverable->isCompleted() ){
                        $this->getDeliverableManager()->complete($deliverable);
                        $hasCompletion = true;
                    }
                    $this->getDeliverableManager()->save($deliverable);
                }
                
                // If at least one deliverable is complete, redirect to the Open Bill
                if( $hasCompletion )
                    return $this->redirect($this->generateUrl("client_bill", array("idRelation" => $idRelation)));
                
                // Avoid form resubmitting
                return $this->redirect($this->generateUrl("client_progress", array("idRelation" => $idRelation)));
            }
        }
        return array(
            'deliverables' => $deliverables,
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
    private function createProgressForm( $relation, $deliverables ) {
        
        $form = $this->createForm(
                new RelationProgressType(), 
                null, 
                array(
                    'action' => $this->generateUrl( 'client_progress', array('idRelation' => $relation->getId()) ),
                    'method' => 'POST',
                    'deliverables' => $deliverables
                ));

        $form->add( 'submit', 'submit', array('label' => 'Save') );

        
        return $form;
    }

}
