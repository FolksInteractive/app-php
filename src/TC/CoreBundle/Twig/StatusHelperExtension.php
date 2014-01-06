<?php

namespace TC\CoreBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use TC\CoreBundle\Entity\Invoice;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\OrderManager;
use TC\CoreBundle\Model\RFPManager;
use TC\UserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class StatusHelperExtension extends Twig_Extension {

    /**
     * @var ContainerInterface
     */
    private $container;
    
    public function __construct( ContainerInterface $container = null ){
        $this->container = $container;
    }
    
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction( 'rfp_status',      array($this, 'getRFPStatus') ),
            new Twig_SimpleFunction( 'order_status',    array($this, 'getOrderStatus') ),
            new Twig_SimpleFunction( 'invoice_status',     array($this, 'getInvoiceStatus') ),
            
            new Twig_SimpleFunction( 'is_cancellable',  array($this, 'isCancellable') ),
            new Twig_SimpleFunction( 'is_declinable',   array($this, 'isDeclinable') ),
            new Twig_SimpleFunction( 'is_editable',     array($this, 'isEditable') ),
            new Twig_SimpleFunction( 'is_sendable',     array($this, 'isSendable') ),
            new Twig_SimpleFunction( 'is_purchasable',  array($this, 'isPurchasable') ),
            new Twig_SimpleFunction( 'is_reopenable',   array($this, 'isReopenable') ),
        );
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter( 'status', array($this, 'getStatus') ),
        );
    }
    
    public function isCancellable( $resource ){        
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isCancellable($resource);
        }
        
        if( $resource instanceOf RFP ){
            return $this->getRFPManager()->isCancellable($resource);
        }
    }

    public function isDeclinable( $resource ){
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isDeclinable($resource);
        }
        
        if( $resource instanceOf RFP ){
            return $this->getRFPManager()->isDeclinable($resource);
        }
    }

    public function isEditable( $resource ){
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isEditable($resource);
        }
        
        if( $resource instanceOf RFP ){
            return $this->getRFPManager()->isEditable($resource);
        }
        
        if( $resource instanceOf Invoice ){
            return $this->getInvoiceManager()->isEditable($resource);
        }
    }
    
    public function isSendable( $resource ){
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isSendable($resource);
        }
        
        if( $resource instanceOf RFP ){
            return $this->getRFPManager()->isSendable($resource);
        }
    }
    
    public function isPurchasable( $resource ){
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isPurchasable($resource);
        }
    }

    public function isReopenable( $resource ){
        if( $resource instanceOf Order ){
            return $this->getOrderManager()->isReopenable($resource);
        }
        
        if( $resource instanceOf RFP ){
            return $this->getRFPManager()->isReopenable($resource);
        }
    }
    
    public function getStatus( $resource ){
        if( $resource instanceOf RFP ){
            return "tc-".$this->getRFPStatus($resource);
        }
        
        if( $resource instanceOf Order ){
            return "tc-".$this->getOrderStatus($resource);
        }
    }
    
    /**    
     * 
     * @param Invoice $invoice
     * @return array
     */
    public function getInvoiceStatus(Invoice $invoice) {
        $state ="";
                        
        return $state;
    }
    
    /**    
     * State that should never happen :
     *  - tc-draft-declined
     *  - tc-closed-declined
     * If it happens it means that their is a breakdown in the business logic
     *
     * @param RFP $rfp
     * @return array
     */
    public function getRFPStatus(RFP $rfp) {
        $state ="";
        
        if($rfp->getOrder() && $rfp->getOrder()->getReady()){
            $state = "closed";            
        }elseif($rfp->getReady()){
            $state = "sent";            
        }else{
            $state = "draft";        
        }     
                    
        if($rfp->getDeclined()){
            $state .= "-declined";
        }elseif($rfp->getCancelled()){
            $state .= "-cancelled";
        }
                
        return $state;
    }
    
    /**     
     * State that should never happen :
     *  - tc-draft-declined
     *  - tc-closed-declined (because a proposal cannot be purchased and declined at the same time)
     * If it happens it means that their is a breakdown in the business logic
     *
     * @param Order $order
     * @return array
     */
    public function getOrderStatus(Order $order) {
        $state ="";
        
        if($order->getApproved()){
            $state = "closed";            
        }elseif($order->getReady()){
            $state = "sent";            
        }else{
            $state = "draft";        
        }    
              
        
        if($order->getDeclined()){
            $state .= "-declined";
        }elseif($order->getCancelled()){
            $state .= "-cancelled";
        }
        
        return $state;
    }

    /**
     * @return User
     */
    private function getUser() {
        return $this->container->get('security.context')->getToken()->getUser();
    }

    /**
     * @return Workspace
     */
    private function getWorkspace() {
        return $this->getUser()->getWorkspace();
    }
    
    /**
     * @return OrderManager
     */
    private function getOrderManager(){
        return $this->container->get('tc.manager.order');
    }
    
    /**
     * @return RFPManager
     */
    private function getRFPManager(){
        return $this->container->get('tc.manager.rfp');
    }
    
    /**
     * @return string
     */
    public function getName() {
        return 'tc_status_helper_extension';
    }
}

?>
