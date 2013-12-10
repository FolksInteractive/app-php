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
            new Twig_SimpleFunction( 'rfp_status_list', array($this, 'getRFPStatusList') ),
            new Twig_SimpleFunction( 'order_status_list', array($this, 'getOrderStatusList') ),
        );
    }

    public function getFilters() {
        return array(
            new Twig_SimpleFilter( 'status', array($this, 'getStatus') ),
        );
    }
    
    public function getStatus( $resource ){
        if( $resource instanceOf RFP ){
            $list = array_reverse($this->getRFPStatusList($resource));            
            return implode(" " , $this->appendPrefix($list));
        }
        
        if( $resource instanceOf Order ){
            $list = $this->getOrderStatusList($resource);
            return implode(" " , $this->appendPrefix($list));
        }
    }
    /**
     * @param RFP $rfp
     * @return array
     */
    public function getRFPStatusList(RFP $rfp) {
        $list = array();
        
        $list[] = $rfp->getReady() ?  "ready" : "draft";
        
        if($rfp->getRefused())
            $list[] = "refused";        
       
        if($rfp->getCancelled() || $rfp->getRefused())
            $list[] = "cancelled";
        
        if($rfp->getOrder()){
            
            if($rfp->getOrder()->getReady())
                $list[] = "proposed";
            
            if($rfp->getOrder()->getApproved()) 
                $list[] = "purchased";
            
            if($rfp->getOrder()->getRefused()) 
                $list[] = "cancelled";
        }
        
        return $list;
    }
    /**
     * @param Order $order
     * @return array
     */
    public function getOrderStatusList(Order $order) {
        $list = array();
        $list[] = $order->getReady() ?  "ready" : "draft";
        
        if($order->getRefused())
            $list[] = "refused";
        
        if($order->getCancelled())
            $list[] = "cancelled";
        
        if($order->getApproved())
            $list[] = "purchased";
        
        return $list;
    }
    
    /**
     * Appends tc- prefix to list of string
     * @param array $list
     */
    private function appendPrefix( array $list, $prefix = "tc-" ){
        foreach ($list as $key => $value){
            $list[$key] = $prefix.$value;
        }
        
        return $list;
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
