<?php

namespace TC\CoreBundle\Controller\Vendor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Pricebook controller.
 *
 * @Route("/pb")
 */
class PricebookController extends Controller
{
    /**
     * @Route("/", name="vendor_pricebook")
     * @Template("TCCoreBundle:Vendor:Pricebook/soon.html.twig")
     */
    public function soonAction()
    {
         return array(
        );
    }

}
