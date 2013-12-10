<?php

namespace TC\CoreBundle\Twig;

use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Entity\Workspace;
use TC\UserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class StatusHelperExtension extends Twig_Extension {

    /**
     * @var SecurityContext 
     */
    private $securityContext;

    public function __construct( SecurityContext $securityContext ) {
        $this->securityContext = $securityContext;
    }

    public function getFunctions() {
        return array(
            new Twig_SimpleFunction( 'rfp_status', array($this, 'getRFPStatus') ),
            new Twig_SimpleFunction( 'order_status', array($this, 'getOrderStatus') ),
        );
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter( 'status', array($this, 'getStatus') ),
        );
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
     * @param RFP $rfp
     * @return array
     */
    public function getRFPStatus(RFP $rfp) {
        $state ="";
        
        if($rfp->getOrder()){
            $state = "closed";            
        }elseif($rfp->getReady()){
            $state = "sent";            
        }else{
            $state = "draft";        
        }     
                    
        if($rfp->getRefused()){
            $state .= "-refused";
        }elseif($rfp->getCancelled()){
            $state .= "-cancelled";
        }
                
        return $state;
    }
    /**
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
                    
        if($order->getRefused()){
            $state .= "-refused";
        }elseif($order->getCancelled()){
            $state .= "-cancelled";
        }
        
        return $state;
    }

    /**
     * @return User
     */
    private function getUser() {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * @return Workspace
     */
    private function getWorkspace() {
        return $this->getUser()->getWorkspace();
    }

    public function getName() {
        return 'tc_status_helper_extension';
    }

}

?>
