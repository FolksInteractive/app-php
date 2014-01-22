<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\RFPController as BaseController;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Form\RFPDeclinalType;

/**
 * RFP controller.
 *
 * @Route("/clients/{idRelation}/rfps")
 */
class RFPController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="client_rfps")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:rfps_client.html.twig")
     */
    public function rfpsAction( $idRelation ) {
        
        $relation = $this->getRelationManager()->findClient( $idRelation );

        $rfps = $this->getRFPManager()->findAllByClient( $relation );
        
        return array(
            'relation' => $relation,
            'rfps' => $rfps
        );
    }
    
    /**
     * Finds and displays a RFP.
     *
     * @Route("/{idRFP}", name="client_rfp_show")
     * @Template("TCCoreBundle:RFP:rfp_show_client.html.twig")
     */
    public function showAction( $idRelation, $idRFP ) {
        
        $relation = $this->getRelationManager()->findClient( $idRelation );
        
        /**
         * @var RFP $rfp
         */
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        return array(
            'rfp' => $rfp,
            'relation' => $relation
        );
    }

    /**
     * Cancel a RFP.
     *
     * @Route("/{idRFP}/decline", name="client_rfp_decline")
     * @Template("TCCoreBundle:RFP:rfp_decline_client.html.twig")
     */
    public function declineAction( Request $request, $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findClient( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        // If RFP is already cancelled redirect to rfp list
        if($rfp->getDeclined())
            return $this->redirect( $this->generateUrl( 'client_rfps', array('idRelation' => $idRelation) ) );

        $form = $this->createDeclineForm( $rfp );

        if ( $request->getMethod() === "PUT" ){
            
            $form->handleRequest( $request );
            
            if($form->isValid() ) {
                $refusal = $form->getData();
                $this->getRFPManager()->decline( $rfp, $refusal );
                $this->getRFPManager()->save( $rfp );

                return $this->redirect( $this->generateUrl( 'client_rfps', array('idRelation' => $idRelation) ) );
            }
        }

        return array(
            'form' => $form->createView(),
            'rfp' => $rfp,
            'relation' => $relation
        );
    }
    
    /** ******************************* */
    /*              FORMS               */
    /** ******************************* */
    
    /**
     * Creates a form to decline a RFP.
     *
     * @param RFP $rfp The rfp
     *
     * @return Form The form
     */
    private function createDeclineForm( RFP $rfp ) {
        $action = $this->generateUrl( 'client_rfp_decline', array('idRelation' => $rfp->getRelation()->getId(), 'idRFP' => $rfp->getId()) );
        
        $form = $this->createForm( new RFPDeclinalType(), null, array(
            'action' => $action,
            'method' => 'PUT',
        ) );
        
        $form->add( 'submit', 'submit' );

        return $form;
    }
}
