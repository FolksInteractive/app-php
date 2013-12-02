<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\OrderController as BaseController;

/**
 * Order controller.
 *
 * @Route("/r/{idRelation}/progress")
 */
class ProgressController extends BaseController {

    /**
     * Displays the work in progress of a relation
     *
     * @Route("/", name="client_relation_progress")
     * @Template("TCCoreBundle:Relation:relation_progress_client.html.twig")
     */
    public function progressAction( Request $request, $idRelation ) {

        $relation = $this->getRelationManager()->findByClient( $idRelation );

        $deliverables = $this->getDeliverableManager()->findAllInProgressByRelation($relation);
        
        return array(
            'relation' => $relation,
            'deliverables' => $deliverables
        );
    }
    
}
