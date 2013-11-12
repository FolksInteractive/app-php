<?php

namespace TC\ApiBundle\Controller;

use TC\ApiBundle\Form\PricebookItemType;
use TC\ApiBundle\Entity\PricebookItem;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;

/**
 * https://github.com/FriendsOfSymfony/FOSRestBundle/blob/master/Resources/doc/5-automatic-route-generation_single-restful-controller.md
 *  
 * @RouteResource("Pricebook")
 */
class PricebookController extends Controller {

    public function getAction() {
        $priceBook = $this->getPricebookManager()->getPricebook();

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $priceBook );
        $view->setSerializationContext( $this->getContext( array("pricebook") ));
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    /**
     * @return PricebookManager
     */
    private function getPricebookManager() {
        return $this->container->get( "tc.manager.pricebook" );
    }
    
    private function getContext( $groups ){
        $context = new SerializationContext();
        $context->setVersion("0");
        $context->setGroups($groups);
        
        return $context;
    }
}

?>
