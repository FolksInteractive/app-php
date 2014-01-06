<?php

namespace TC\CoreBundle\Twig;

use Doctrine\Common\Collections\ArrayCollection;
use LogicException;
use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\RelationManager;
use TC\UserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleFunction;

class StatsExtension extends Twig_Extension {

    /**
     * @var SecurityContext 
     */
    private $securityContext;

    /**
     *
     * @var RelationManager
     */
    private $rm;
    
    /**
     * 
     * @param SecurityContext $securityContext
     * @param RelationManager $rm
     */    
    public function __construct( SecurityContext $securityContext, RelationManager $rm) {
        $this->securityContext = $securityContext;
        $this->rm = $rm;
    }
    
    /**
     * @return array
     */
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction( '', array($this, '') ),
        );
    }
    
    private function getRelationStats(Relation $relation){
        $stats = $this->createStats();
        
        if( !$relation->getOrders()->count() <= 0 )
            return stats;
            
        $stats["waiting"] = $this->getDeliverablesTotal($order->getDeliverablesTodo());
        $stats["progress"] = $this->getDeliverablesTotal($order->getDeliverablesTodo());
        $stats["invoiced"] = $this->getDeliverablesTotal($order->getDeliverablesCompleted());
        $stats["all"] = $this->getDeliverablesTotal($order->getDeliverables());
        
        return $stats;
    }
    
    private function getOrderStats(Order $order){
        if( !$order->isApproved() )
            return new LogicException("An order must be approved to have stats.");
            
        $stats = $this->createStats();
        unset($stats["waiting"]);
        
        $stats["progress"] = $this->getDeliverablesTotal($order->getDeliverablesTodo());
        $stats["invoiced"] = $this->getDeliverablesTotal($order->getDeliverablesCompleted());
        $stats["all"] = $this->getDeliverablesTotal($order->getDeliverables());
        
        return $stats;
    }
    
    private function getDeliverablesTotal(ArrayCollection $deliverables){
        $total = 0;
        foreach( $deliverables as $key=>$deliverable ){
            $total += $deliverable->getTotal();
        }
    }
    
    private function createStats(){
        return array(
            "waiting" => 0,
            "progress" => 0,
            "completed" => 0,
            "all" => 0,
        );  
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

    /**
     * @return string
     */
    public function getName() {
        return 'tc_relation_helper';
    }

}

?>
