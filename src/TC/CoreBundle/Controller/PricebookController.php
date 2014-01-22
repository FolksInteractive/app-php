<?php

namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Pricebook controller.
 *
 * @Route("/pricebook")
 */
class PricebookController extends Controller
{
    /**
     * @Route("/", name="pricebook")
     * @Template("TCCoreBundle:Pricebook:soon.html.twig")
     */
    public function soonAction()
    {
         return array(
        );
    }

}
