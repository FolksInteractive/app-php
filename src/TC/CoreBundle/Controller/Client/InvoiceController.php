<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\InvoiceController as BaseController;

/**
 * Relation controller.
 *
 * @Route("/r/{idRelation}")
 */
class InvoiceController extends BaseController {

    /**
     * @Route("/bill", name="client_relation_invoice")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_bill_client.html.twig")
     */
    public function billAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllToInvoice($relation);
        
        return array(
            'deliverables' => $deliverables,
            'relation' => $relation
        );
    }

    /**
     * Displays the allt he closed invoices of a relation
     *
     * @Route("/invoices", name="client_relation_invoices")
     * @Method("GET")
     * @Template("TCCoreBundle:Relation:relation_invoices_client.html.twig")
     */
    public function invoicesAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );
        
        $invoices = $this->getInvoiceManager()->findAllByRelation( $relation );

        return array(
            'invoices' => $invoices,
            'relation' => $relation
        );
    }
    
    /**
     * Display a closed invoice
     *
     * @Route("/invoices/{idInvoice}", name="client_invoice_show")
     * @Method("GET")
     * @Template("TCCoreBundle:Invoice:invoice_show_client.html.twig")
     */
    public function invoiceAction ( $idInvoice, $idRelation) {
        
        $relation = $this->getRelationManager()->findByClient( $idRelation );        
        $invoice = $this->getInvoiceManager()->findByRelation( $relation, $idInvoice );
        
        return array(
            'relation' => $relation,
            'invoice' => $invoice
        );
    }
}
