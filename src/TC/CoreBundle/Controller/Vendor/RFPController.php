<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;
use TC\CoreBundle\Controller\RFPController as BaseController;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Entity\Relation;

/**
 * RFP controller.
 *
 * @Route("/r/{idRelation}/rfps")
 */
class RFPController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="vendor_relation_rfps")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_rfps_vendor.html.twig")
     */
    public function rfpsAction( $idRelation ) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $rfps = $this->getRFPManager()->findAllForVendor( $relation );
        
        return array(
            'relation' => $relation,
            'rfps' => $rfps
        );
    }
    
    /**
     * Finds and displays a RFP.
     *
     * @Route("/{idRFP}", name="vendor_rfp_show")
     * @Template("TCCoreBundle:RFP:rfp_show_vendor.html.twig")
     */
    public function showAction( $idRelation, $idRFP ) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        
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
     * @Route("/{idRFP}/refuse", name="vendor_rfp_refuse")
     * @Template("TCCoreBundle:RFP:rfp_refuse_vendor.html.twig")
     */
    public function refuseAction( Request $request, $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        // If RFP is already cancelled redirect to rfp list
        if($rfp->getRefused())
            return $this->redirect( $this->generateUrl( 'vendor_relation_rfps', array('idRelation' => $idRelation) ) );

        $form = $this->createRefuseForm( $rfp );

        if ( $request->getMethod() === "PUT" ){
            
            $form->handleRequest( $request );
            
            if($form->isValid() ) {
                $refusal = $form->getData();
                $this->getRFPManager()->refuse( $rfp, $refusal );
                $this->getRFPManager()->save( $rfp );

                return $this->redirect( $this->generateUrl( 'vendor_relation_rfps', array('idRelation' => $idRelation) ) );
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
     * Creates a form to refuse a RFP.
     *
     * @param RFP $rfp The rfp
     *
     * @return Form The form
     */
    private function createRefuseForm( RFP $rfp ) {
        $action = $this->generateUrl( 'vendor_rfp_refuse', array('idRelation' => $rfp->getRelation()->getId(), 'idRFP' => $rfp->getId()) );
        
        $builder = $this->createFormBuilder( null , array(
                    'action' => $action,
                    'method' => 'PUT',
                ) );
                
        if( $rfp->getReady() ){
            $builder->add( 'why', 'choice', array(
                "choices" => array(
                    "This is not part of my expertise.",
                    "I do not have enough resources to meet this demand.",
                    "I do not have the time to work on it.",
                    "What is requested is in progress or is already done."
                ),
                'expanded' => true,
            ) )

            ->add( 'other', 'textarea', array( "required" => false ) );
        }
        
        $builder->add( 'submit', 'submit' );

        return $builder->getForm();
    }
}
