<?php

namespace FLS\TimeCrumbsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AppController extends Controller {

    /**
     * @Route("/", defaults={"_format"="html"}, name="app")
     * @Route("/p/{idProject}/", defaults={"_format"="html"}, name="app_project")
     * @Route("/p/{idProject}/{idOrder}", defaults={"_format"="html"}, name="app_project_order")
     * @Template()
     */
    public function indexAction() {
        return array();//$this->redirect( $this->generateUrl( "app_project"));
    }
    
    /**
     * @Route("/test")
     */
    public function testAction(){
    
     
    }
    
    /**
     * @return ProjectManager
     */
    private function getProjectManager() {
        return $this->container->get( "fls_timecrumbs.manager.project" );
    }
}
