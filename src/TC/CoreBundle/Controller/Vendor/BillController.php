<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\BillController as BaseController;
use TC\CoreBundle\Entity\Relation;

/**
 * Relation controller.
 *
 * @Route("/r/{idRelation}")
 */
class BillController extends BaseController {

    /**
     * Displays the work in progress of a relation
     *
     * @Route("/bill", name="vendor_relation_bill")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_bill_vendor.html.twig")
     */
    public function billAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $form = $this->createCloseBillForm( $relation );

        return array(
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * @param integer $idRelation The Id of the relation of the open bill
     * 
     * @Route("/bill", name="vendor_relation_bill_close")
     * @Method("POST")
     * @Template("TCCoreBundle:Relation:relation_bill_vendor.html.twig")
     */
    public function closeBillAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $form = $this->createCloseBillForm( $relation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getBillManager()->close( $relation );

            // Redirect to list of invoices
            $this->redirect($this->generateUrl("vendor_relation_invoices",array("idRelation"=>$relation->getId())));
        }

        return array(
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * Displays the allt he closed bills of a relation
     *
     * @Route("/invoices", name="vendor_relation_invoices")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_invoices_vendor.html.twig")
     */
    public function invoicesAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        return array(
            'relation' => $relation
        );
    }
    
    /**
     * Display a closed bill
     *
     * @Route("/invoice/{idBill}", name="vendor_invoice_show")
     * @Method("GET")
     * @Template("TCCoreBundle:Invoice:invoice_show_vendor.html.twig")
     */
    public function invoiceAction ( $idBill, $idRelation) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );        
        $bill = $this->getBillManager()->findClosedByRelation( $relation, $idInvoice );
        
        return array(
            'relation' => $relation,
            'bill' => $bill
        );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * @param Relation $relation The order
     *
     * @return Form The form
     */
    private function createCloseBillForm( Relation $relation ) {

        $form = $this->createFormBuilder( null, array(
                    'action' => $this->generateUrl( 'vendor_relation_bill_close', array('idRelation' => $relation->getId()) ),
                    'method' => 'POST',
                ) )
                ->add( 'submit', 'submit', array('label' => 'Close bill') )
                ->getForm();

        return $form;
    }
}
