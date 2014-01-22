<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\RFPController as BaseController;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Form\RFPType;

/**
 * RFP controller.
 *
 * @Route("/vendors/{idRelation}/rfps")
 */
class RFPController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="vendor_rfps")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:rfps_vendor.html.twig")
     */
    public function rfpsAction( $idRelation ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );

        $rfps = $this->getRFPManager()->findAllByVendor( $relation );

        return array(
            'relation' => $relation,
            'rfps' => $rfps
        );
    }

    /**
     * Displays a form to edit an existing RFP.
     *
     * @Route("/new", name="vendor_rfp_new")
     * @Route("/{idRFP}/edit", name="vendor_rfp_edit")
     * @Method("GET")
     * @Template("TCCoreBundle:RFP:rfp_edit_vendor.html.twig")
     */
    public function editAction( $idRelation, $idRFP = null ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );

        if ( $idRFP != null ) {
            $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        } else {
            $rfp = $this->getRFPManager()->create( $relation );
        }
        $form = $this->createRFPForm( $rfp );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return array(
            'rfp' => $rfp,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }

    /**
     * Edits an existing RFP.
     *
     * @Route("/", name="vendor_rfp_create")
     * @Route("/{idRFP}/edit", name="vendor_rfp_update", defaults={"idRFP"=null})
     * @Method({"POST", "PUT"})
     * @Template("TCCoreBundle:RFP:rfp_edit_vendor.html.twig")
     */
    public function updateAction( Request $request, $idRelation, $idRFP = null ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );

        if ( $idRFP != null ) {
            $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        } else {
            $rfp = $this->getRFPManager()->create( $relation );
        }

        $form = $this->createRFPForm( $rfp );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {

            $this->getRFPManager()->save( $rfp );
            
            if( $form->get('save_as_ready')->isClicked())
                return $this->forward( 'TCCoreBundle:Vendor/RFP:send', array('idRelation' => $idRelation, 'idRFP' => $rfp->getId()) );

            return $this->redirect( $this->generateUrl( 'vendor_rfp_show', array('idRelation' => $idRelation, 'idRFP' => $rfp->getId()) ) );
        }

        return array(
            'rfp' => $rfp,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }
            
    /**
     * Sends a RFP to vendor
     *
     * @Route("/{idRFP}/send", name="vendor_rfp_send")
     * @Method("GET")
     */
    public function sendAction( $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        
        $this->getRFPManager()->ready($rfp);
        $this->getRFPManager()->save($rfp);
        
        return $this->redirect( $this->generateUrl( 'vendor_rfp_show', array('idRelation' => $idRelation, 'idRFP' => $rfp->getId()) ) );
    }

    /**
     * Finds and displays a RFP.
     *
     * @Route("/{idRFP}", name="vendor_rfp_show")
     * @Template("TCCoreBundle:RFP:rfp_show_vendor.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        return array(
            'rfp' => $rfp,
            'relation' => $relation
        );
    }

    /**
     * Cancel a RFP.
     *
     * @Route("/{idRFP}/cancel", name="vendor_rfp_cancel")
     * @Template("TCCoreBundle:RFP:rfp_cancel_vendor.html.twig")
     */
    public function cancelAction( Request $request, $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        // If RFP is already cancelled redirect to rfp list
        if($rfp->getCancelled())
            return $this->redirect( $this->generateUrl( 'vendor_rfps', array('idRelation' => $idRelation) ) );

        $form = $this->createCancelForm( $rfp );

        if ( $request->getMethod() === "PUT" ){
            
            $form->handleRequest( $request );
            
            if($form->isValid() ) {
                $cancellation = $form->getData();
                $this->getRFPManager()->cancel( $rfp, $cancellation );
                $this->getRFPManager()->save( $rfp );

                return $this->redirect( $this->generateUrl( 'vendor_rfps', array('idRelation' => $idRelation) ) );
            }
        }

        return array(
            'form' => $form->createView(),
            'rfp' => $rfp,
            'relation' => $relation
        );
    }
    
    /**
     * Reopen a Order.
     *
     * @Route("/{idRFP}/reopen", name="vendor_rfp_reopen")
     */
    public function reopenAction( $idRelation, $idRFP ) {
        
        $relation = $this->getRelationManager()->findVendor( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        
        $this->getRFPManager()->reopen( $rfp );
        $this->getRFPManager()->save( $rfp );
        
        return $this->redirect( $this->generateUrl( 'vendor_rfp_show', array('idRelation' => $idRelation, 'idRFP' => $idRFP) ) );
    }

    /** ******************************* */
    /*              FORMS               */
    /** ******************************* */

    /**
     * Creates a form to edit a RFP.
     *
     * @param RFP $rfp The rfp
     *
     * @return Form The form
     */
    private function createRFPForm( RFP $rfp ) {

        if ( !$rfp->getId() ) {
            $action = $this->generateUrl( 'vendor_rfp_create', array('idRelation' => $rfp->getRelation()->getId()) );
        } else {
            $action = $this->generateUrl( 'vendor_rfp_update', array('idRelation' => $rfp->getRelation()->getId(), 'idRFP' => $rfp->getId()) );
        }

        $form = $this->createForm( new RFPType(), $rfp, array(
            'action' => $action,
            'method' => 'PUT',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Update') );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return $form;
    }

    /**
     * Creates a form to cancel a RFP.
     *
     * @param RFP $rfp The rfp
     *
     * @return Form The form
     */
    private function createCancelForm( RFP $rfp ) {
        $action = $this->generateUrl( 'vendor_rfp_cancel', array('idRelation' => $rfp->getRelation()->getId(), 'idRFP' => $rfp->getId()) );
        
        $builder = $this->createFormBuilder( null , array(
                    'action' => $action,
                    'method' => 'PUT',
                ) );
                
        if( $rfp->getReady() ){
            $builder->add( 'why', 'choice', array(
                "choices" => array(
                    "My need was addressed in another way",
                    "I no longer need this",
                    "My need has changed, a new RFP will be available eventually"
                ),
                'expanded' => true,
            ) )

            ->add( 'other', 'textarea', array( "required" => false ) );
        }
        
        $builder->add( 'submit', 'submit' );

        return $builder->getForm();
    }

}
