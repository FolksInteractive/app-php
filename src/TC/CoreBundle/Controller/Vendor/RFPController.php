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
     * @Template("TCCoreBundle:Vendor:Relation/rfps.html.twig")
     */
    public function rfpsAction( $idRelation ) {
        
        $relation = $this->getRelationManager()->findByVendor( $idRelation );

        return array(
            'relation' => $relation,
        );
    }
    
    /**
     * Finds and displays a RFP.
     *
     * @Route("/{idRFP}", name="vendor_rfp_show")
     * @Template("TCCoreBundle:Vendor:RFP/show.html.twig")
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
