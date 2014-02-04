<?php

namespace TC\CoreBundle\Controller\Vendor;

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
 * Vendor controller.
 *
 * @Route("/vendors")
 */
class VendorController extends BaseController {

    /**
     * Listing of Relations.
     *
     * @Route("/", name="vendor_index")
     * @Route("/{idRelation}", name="vendor_overview", requirements={"idRelation" = "\d+"})
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:overview_vendor.html.twig")
     */
    public function indexAction(Request $request, $idRelation = null)
    {
        /* @var $saleReporter SalesReporter */
        $salesReporter = $this->container->get('tc.reporter.sales');
        
        /* @var $prodReporter ProductivityReporter */
        $prodReporter = $this->container->get('tc.reporter.productivity');
        
        /* @var $accountingReporter AccountingReporter */
        $accountingReporter = $this->container->get('tc.reporter.accounting');
        
        $relations  = $this->getRelationManager()->findAllVendors();        

        if( $idRelation ){
            $relation = $this->getRelationManager()->findVendor($idRelation);
        }else{
            $relation = $relations->first() ? $relations->current() : null;   
        }
        
        return array(
            'delivery_flow'         => $prodReporter->getDeliveryFlow( $relation ),
            'productivity_flow'     => $prodReporter->getProductivityFlow( $relation ),
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
     * @Route("/", name="vendor_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Relation:new_vendor.html.twig")
     */
    public function createAction( Request $request ) {
        $relation = $this->getRelationManager()->createVendor();
        $form = $this->createCreateForm( $relation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getRelationManager()->save($relation, $form->get('notify')->getData());

            return $this->redirect( $this->generateUrl( 'vendor_rfps', array('idRelation' => $relation->getId()) ) );
        }

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new relation.
     *
     * @Route("/new", name="vendor_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:new_vendor.html.twig")
     */
    public function newAction() {
        $relation = $this->getRelationManager()->createVendor();
        $form = $this->createCreateForm( $relation );

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Archive a relation.
     *
     * @Route("/{idRelation}", name="vendor_archive")
     * @Method("POST")
     */
    public function archiveAction( Request $request, $idRelation ) {
        $relation = $this->getRelationManager()->findVendor($idRelation);
        
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
            'action' => $this->generateUrl( 'vendor_create', array('idRelation' => $relation->getId()) ),
            'method' => 'POST',
                ) );
        
        $form->remove('client');
        
        $form->add( 'submit', 'submit', array('label' => 'Create') );

        return $form;
    }
}