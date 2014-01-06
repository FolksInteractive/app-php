<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\InvoiceController as BaseController;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Invoice;

/**
 * Relation controller.
 *
 * @Route("/r/{idRelation}")
 */
class InvoiceController extends BaseController {

    /**
     * Displays the work in progress of a relation
     *
     * @Route("/bill", name="vendor_relation_invoice")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_bill_vendor.html.twig")
     */
    public function billAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllToInvoice($relation);
        
        $form = $this->createCloseInvoiceForm( $relation, $deliverables );

        return array(
            'deliverables'  => $deliverables,
            'relation'      => $relation,
            'form'          => $form->createView()
        );
    }

    /**
     * @param integer $idRelation The Id of the relation
     * 
     * @Route("/invoice", name="vendor_relation_invoice_close")
     * @Method("POST")
     * @Template("TCCoreBundle:Relation:relation_invoice_vendor.html.twig")
     */
    public function closeInvoiceAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllToInvoice($relation);
        
        $form = $this->createCloseInvoiceForm( $relation, $deliverables );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $invoice = $this->getInvoiceManager()->create( $relation );
            foreach( $form->get('deliverables')->getData() as $key=>$deliverable ){
                $this->getInvoiceManager()->addDeliverable( $invoice, $deliverable );
            }
            $this->getInvoiceManager()->save($invoice);
            
            // Redirect to list of invoices
            return $this->redirect($this->generateUrl("vendor_relation_invoices",array("idRelation"=>$relation->getId())));
        }

        return array(
            'deliverables' => $deliverables,
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * Displays the allt he closed invoices of a relation
     *
     * @Route("/invoices", name="vendor_relation_invoices")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_invoices_vendor.html.twig")
     */
    public function invoicesAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByVendor( $idRelation );
        
        $invoices = $this->getInvoiceManager()->findAllByRelation( $relation );

        return array(
            'invoices' => $invoices,
            'relation' => $relation
        );
    }
    
    /**
     * Display a closed invoice
     *
     * @Route("/invoices/{idInvoice}", name="vendor_invoice_show")
     * @Method("GET")
     * @Template("TCCoreBundle:Invoice:invoice_show_vendor.html.twig")
     */
    public function invoiceAction ( $idInvoice, $idRelation) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );        
        $invoice = $this->getInvoiceManager()->findByRelation( $relation, $idInvoice );
        
        return array(
            'relation' => $relation,
            'invoice' => $invoice
        );
    }
    
    /**
     * Display a closed invoice
     *
     * @Route("/invoices/{idInvoice}/edit", name="vendor_invoice_edit")
     * @Template("TCCoreBundle:Invoice:invoice_edit_vendor.html.twig")
     */
    public function editAction ( Request $request, $idInvoice, $idRelation) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );        
        $invoice = $this->getInvoiceManager()->findByRelation( $relation, $idInvoice );
        
        $form = $this->createEditForm( $invoice );
        
        if( $request->getMethod() === "PUT" ){
            $form->handleRequest( $request );

            if ( $form->isValid() ) {
                $this->getInvoiceManager()->save($invoice);

                // Redirect to invoice
                return $this->redirect($this->generateUrl("vendor_invoice_show",array("idRelation"=>$relation->getId(), "idInvoice"=>$invoice->getId() )));
            }
        }
        
        return array(
            'relation' => $relation,
            'invoice' => $invoice,
            'form' => $form->createView()
        );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * Creates a form to edit a Invoice.
     *
     * @param Invoice $invoice The Invoice
     *
     * @return Form The form
     */
    private function createEditForm( Invoice $invoice ) {
        
        $action = $this->generateUrl( 'vendor_invoice_edit', array('idRelation' => $invoice->getRelation()->getId(), 'idInvoice' => $invoice->getId()) );
        
        $form = $this->createForm( new \TC\CoreBundle\Form\InvoiceEditType(), $invoice, array(
            'action' => $action,
            'method' => 'PUT',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Update') );

        return $form;
    }
    
    /**
     * @param Relation $relation The order
     *
     * @return Form The form
     */
    private function createCloseInvoiceForm( Relation $relation, $deliverables ) {

        $form = $this->createFormBuilder( null, array(
                    'action' => $this->generateUrl( 'vendor_relation_invoice_close', array('idRelation' => $relation->getId()) ),
                    'method' => 'POST'
                ) )
                ->add('deliverables', 'entity', array(
                    'required'  => true,                
                    'class'     => 'TCCoreBundle:Deliverable',
                    'property'  => 'name',
                    'multiple'  => true,
                    'expanded'  => true,
                    'choices'   => $deliverables
                ))
                ->add( 'submit', 'submit', array('label' => 'Close invoice') )
                ->getForm();

        return $form;
    }
}
