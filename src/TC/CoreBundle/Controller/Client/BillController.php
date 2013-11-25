<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\BillController as BaseController;

/**
 * Relation controller.
 *
 * @Route("/r/{idRelation}")
 */
class BillController extends BaseController {

    /**
     * @Route("/bill", name="client_relation_bill")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Relation/bill.html.twig")
     */
    public function billAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );

        return array(
            'relation' => $relation
        );
    }

    /**
     * Displays the allt he closed bills of a relation
     *
     * @Route("/invoices", name="client_relation_invoices")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Relation/invoices.html.twig")
     */
    public function invoicesAction( $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );

        return array(
            'relation' => $relation
        );
    }
    
    /**
     * Display a closed bill
     *
     * @Route("/invoice/{idBill}", name="client_invoice_show")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Invoice/show.html.twig")
     */
    public function invoiceAction ( $idBill, $idRelation) {
        
        $relation = $this->getRelationManager()->findByClient( $idRelation );        
        $bill = $this->getBillManager()->findClosedByRelation( $relation, $idBill );
        
        return array(
            'relation' => $relation,
            'bill' => $bill
        );
    }
}
