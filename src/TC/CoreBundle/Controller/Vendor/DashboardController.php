<?php

namespace TC\CoreBundle\Controller\Vendor;

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
     * @Route("/", name="vendor_dashboard")
     * @Method("GET")
     * @Template("TCCoreBundle::dashboard_vendor.html.twig")
     */
    public function showAction(  ) {
        $workspace = $this->getWorkspace();

        return array(
            'workspace' => $workspace,
            'relations' => $workspace->getVendorRelations(),
        );
    }

}
