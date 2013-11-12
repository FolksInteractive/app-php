<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use TC\CoreBundle\Controller\DashboardController as BaseController;

/**
 * @Route("/d")
 */
class DashboardController extends BaseController {
/**
     * Display the dashboard
     *
     * @Route("/", name="client_dashboard")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:dashboard.html.twig")
     */
    public function showAction(  ) {
        $workspace = $this->getWorkspace();

        return array(
            'workspace' => $workspace,
            'relations' => $workspace->getClientRelations(),
        );
    }
}
