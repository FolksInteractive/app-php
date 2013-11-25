<?php

namespace TC\CoreBundle\Controller\Client;

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
 * @Route("/r/{idRelation}/rfps")
 */
class RFPController extends BaseController {

    /**
     * Finds and displays a relation.
     *
     * @Route("/", name="client_relation_rfps")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Relation/rfps.html.twig")
     */
    public function rfpsAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );

        return array(
            'relation' => $relation,
        );
    }
    
    /**
     * Displays a form to edit an existing RFP.
     *
     * @Route("/new", name="client_rfp_new")
     * @Route("/{idRFP}/edit", name="client_rfp_edit")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:RFP/edit.html.twig")
     */
    public function editAction( $idRelation, $idRFP = null ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        
        if( $idRFP != null){
            $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        }else{            
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
     * @Route("/", name="client_rfp_create")
     * @Route("/{idRFP}/edit", name="client_rfp_update", defaults={"idRFP"=null})
     * @Method({"POST", "PUT"})
     * @Template("TCCoreBundle:Client:RFP/edit.html.twig")
     */
    public function updateAction( Request $request, $idRelation, $idRFP = null ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        
        if( $idRFP != null){
            $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );
        }else{            
            $rfp = $this->getRFPManager()->create( $relation );
        }

        $form = $this->createRFPForm( $rfp );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            if( $form->get('save_as_ready')->isClicked())
                $this->getRFPManager()->ready( $rfp );
            
            $this->getRFPManager()->save($rfp);

            return $this->redirect( $this->generateUrl( 'client_rfp_show', array('idRelation' => $idRelation, 'idRFP' => $rfp->getId() ) ) );
        }

        return array(
            'rfp' => $rfp,
            'form' => $form->createView(),
            'relation' => $relation,
        );
    }

    /**
     * Finds and displays a RFP.
     *
     * @Route("/{idRFP}", name="client_rfp_show")
     * @Template("TCCoreBundle:Client:RFP/show.html.twig")
     */
    public function showAction( Request $request, $idRelation, $idRFP ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        $rfp = $this->getRFPManager()->findByRelation( $relation, $idRFP );

        return array(
            'rfp' => $rfp,
            'relation' => $relation
        );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
        
    /**
     * Creates a form to edit a RFP.
     *
     * @param RFP $rfp The rfp
     *
     * @return Form The form
     */
    private function createRFPForm( RFP $rfp ) {
        
        if( !$rfp->getId() ){
            $action = $this->generateUrl( 'client_rfp_create', array('idRelation' => $rfp->getRelation()->getId()) );
        }else{
            $action = $this->generateUrl( 'client_rfp_update', array('idRelation' => $rfp->getRelation()->getId(), 'idRFP' => $rfp->getId()) );
        }
        
        $form = $this->createForm( new RFPType(), $rfp, array(
            'action' => $action,
            'method' => 'PUT',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Update') );
        $form->add( 'save_as_ready', 'submit', array('label' => 'Save as ready') );

        return $form;
    }

}
