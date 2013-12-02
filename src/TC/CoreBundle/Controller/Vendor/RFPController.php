<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
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

}
