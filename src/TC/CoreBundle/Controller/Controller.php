<?php
namespace TC\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\DeliverableManager;
use TC\CoreBundle\Model\OrderManager;
use TC\CoreBundle\Model\ProjectManager;
use TC\CoreBundle\Model\RelationManager;
use TC\CoreBundle\Model\RFPManager;
use TC\CoreBundle\Model\ThreadManager;
use TC\CoreBundle\Model\WorkspaceManager;

class Controller extends SymfonyController {
       
    /**
     * @return InvoiceManager
     */
    protected function getInvoiceManager(){
        return $this->container->get('tc.manager.invoice');
    }
   
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
     * @return DeliverableManager
     */
    protected function getDeliverableManager(){
        return $this->container->get('tc.manager.deliverable');
    }
    
    /**
     * @return RFPManager
     */
    protected function getRFPManager(){
        return $this->container->get('tc.manager.rfp');
    }
    
    /**
     * @return ThreadManager
     */
    protected function getThreadManager(){
        return $this->container->get('tc.manager.thread');
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
