<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\InvoiceController as BaseController;

/**
 * Relation controller.
 *
 * @Route("/vendors/{idRelation}")
 */
class InvoiceController extends BaseController {

    /**
     * @Route("/bill", name="vendor_bill")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:bill_vendor.html.twig")
     */
    public function billAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllToInvoice($relation);
        
        return array(
            'deliverables' => $deliverables,
            'relation' => $relation
        );
    }

    /**
     * Displays the allt he closed invoices of a relation
     *
     * @Route("/invoices", name="vendor_invoices")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:invoices_vendor.html.twig")
     */
    public function invoicesAction( $idRelation ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );
        
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
        
        $relation = $this->getRelationManager()->findVendor( $idRelation );        
        $invoice = $this->getInvoiceManager()->findByRelation( $relation, $idInvoice );
        
        return array(
            'relation' => $relation,
            'invoice' => $invoice
        );
    }
}
