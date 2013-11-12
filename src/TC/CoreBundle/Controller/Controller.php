<?php
namespace TC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\OrderManager;
use TC\CoreBundle\Model\ProjectManager;
use TC\CoreBundle\Model\RelationManager;

class Controller extends SymfonyController {
   
    /**
     * @return RelationManager
     */
    protected function getRelationManager(){
        return $this->container->get('tc.manager.relation');
    }
    
    /**
     * @return OrderManager
     */
    protected function getOrderManager(){
        return $this->container->get('tc.manager.order');
    }
    
    /**
     * @return Workspace
     */
    protected function getWorkspace() {
        return $this->getUser()->getWorkspace();
    }
    
    /**
     * 
     * @return ProjectManager
     */
    protected function getProjectManager(){
        return $this->container->get("tc.manager.project");
    }
    
    /**
     * 
     * @return WorkspaceManager
     */
    protected function getWorkspaceManager(){
        return $this->container->get("tc.manager.workspace");
    }
    
    /**
     * @return Validator
     */
    protected function getValidator() {
        return $this->get( 'validator' );
    }
}

?>
