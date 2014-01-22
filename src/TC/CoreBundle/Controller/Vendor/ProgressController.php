<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;

/**
 * Order controller.
 *
 * @Route("/vendors/{idRelation}/progress")
 */
class ProgressController extends BaseController {

    /**
     * Displays the work in progress of a relation
     *
     * @Route("/", name="vendor_progress")
     * @Template("TCCoreBundle:Relation:progress_vendor.html.twig")
     */
    public function progressAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findVendor( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllInProgressByRelation($relation);
        
        return array(
            'relation' => $relation,
            'deliverables' => $deliverables
        );
    }
    
}
