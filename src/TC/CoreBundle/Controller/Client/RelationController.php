<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\RelationController as BaseController;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Form\RelationCreateType;
use TC\CoreBundle\Reporter\ProductivityReporter;
use TC\CoreBundle\Reporter\SalesReporter;

/**
 * Relation controller.
 *
 * @Route("/r")
 */
class RelationController extends BaseController {

    /**
     * Listing of Relations.
     *
     * @Route("/", name="client_relation")
     * @Route("/{idRelation}", name="client_relation_overview")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_index_client.html.twig")
     */
    public function indexAction(Request $request, $idRelation = null)
    {
        /**
         * @var SalesReporter
         */
        $salesReporter = $this->container->get('tc.reporter.sales');
        /**
         * @var ProductivityReporter
         */
        $prodReporter = $this->container->get('tc.reporter.productivity');
        
        $relations  = $this->getRelationManager()->findAllByClient();        

        if( $idRelation ){
            $relation = $this->getRelationManager()->findByClient($idRelation);
        }else{
            $relation = $relations->first();   
        }
        
        return array(
            'productivity_flow'     => $prodReporter->getFlow( $relation ),
            'order_pending_total'   => $salesReporter->getPendingOrdersAmount( $relation ),
            'order_pending_count'   => $salesReporter->countPendingOrders( $relation ),
            'rfp_pending_count'     => $salesReporter->countPendingRFPs( $relation ),
            "relation" => $relation,
            'relations' => $relations
        );
    }
    
    /**
     * Creates a new relation.
     *
     * @Route("/", name="client_relation_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Relation:relation_new_client.html.twig")
     */
    public function createAction( Request $request ) {
        $relation = $this->getRelationManager()->createForClient();
        $form = $this->createCreateForm( $relation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getRelationManager()->save($relation, $form->get('notify')->getData());

            return $this->redirect( $this->generateUrl( 'client_relation_rfps', array('idRelation' => $relation->getId()) ) );
        }

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new relation.
     *
     * @Route("/new", name="client_relation_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_new_client.html.twig")
     */
    public function newAction() {
        $relation = $this->getRelationManager()->createForClient();
        $form = $this->createCreateForm( $relation );

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Archive a relation.
     *
     * @Route("/{idRelation}", name="client_relation_archive")
     * @Method("POST")
     */
    public function archiveAction( Request $request, $idRelation ) {
        $relation = $this->getRelationManager()->findByClient($idRelation);
        
        $form = $this->createArchiveForm( $idRelation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getRelationManager()->archive($relation);
            $this->getRelationManager()->save($relation);
        }

        return $this->redirect( $this->generateUrl( 'dashboard' ) );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * Creates a form to create a Relation.
     *
     * @param Relation $relaiton The relaiton
     *
     * @return Form The form
     */
    protected function createCreateForm( Relation $relation ){
        $form = $this->createForm( new RelationCreateType(), $relation, array(
            'wm' => $this->getWorkspaceManager(),
            'action' => $this->generateUrl( 'client_relation_create', array('idRelation' => $relation->getId()) ),
            'method' => 'POST',
                ) );
        
        $form->remove('client');
        
        $form->add( 'submit', 'submit', array('label' => 'Create') );

        return $form;
    }
}