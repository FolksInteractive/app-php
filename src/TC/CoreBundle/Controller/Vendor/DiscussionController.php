<?php

namespace TC\CoreBundle\Controller\Vendor;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\DiscussionController as BaseController;

/**
 * Discussion controller.
 *
 * @Route("/r/{idRelation}/orders/{idOrder}")
 */
class DiscussionController extends BaseController {

    /**
     * Finds and displays a Order discussion.
     *
     * @Route("/discuss", name="vendor_order_discuss")
     * @Method("GET")
     * @Template("TCCoreBundle:Order/discuss.html.twig")
     */
    public function discussAction( $idRelation, $idOrder ) {
        
        return parent::discussAction($idRelation, $idOrder);
    }

    /**
     * Finds and displays a Order discussion.
     *
     * @Route("/discuss/sync", name="vendor_order_discuss_sync", defaults={"_format": "json"})
     * @Method("GET")
     */
    public function syncDiscussAction( Request $request, $idRelation, $idOrder ) {
        
        return parent::syncDiscussAction($request, $idRelation, $idOrder);
    }

    /**
     * Finds and displays a Order discussion.
     *
     * @Route("/discuss", name="vendor_order_comment")
     * @Method("POST")
     * @Template("TCCoreBundle:Vendor:Order:discuss.html.twig")
     */
    public function commentAction( Request $request, $idRelation, $idOrder ) {
        
        return parent::commentAction($request, $idRelation, $idOrder);
    }
}