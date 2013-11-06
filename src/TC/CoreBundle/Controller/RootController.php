<?php
namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class RootController extends Controller {

    /**
     * Display the dashboard
     *
     * @Route("/", name="root")
     * @Method("GET")
     * @Template("TCCoreBundle::root.html.twig")
     */
    public function rootAction(  ) {
        return array(
        );
    }
}
