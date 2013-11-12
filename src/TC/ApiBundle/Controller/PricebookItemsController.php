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
 * @RouteResource("Pricebook/Item")
 */
class PricebookItemsController extends Controller {

    public function cgetAction() {
        $itemList = $this->getPricebookManager()->getPricebook();

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $itemList );
        $view->setSerializationContext( $this->getContext( array("pricebookitem") ));
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getAction( $id ) {
        $item = $this->getPricebookManager()->findItem( $id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $item );
        $view->setSerializationContext( $this->getContext( array("pricebookitem") ));
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function postAction() {
        $item = $this->getPricebookManager()->createItem();
        return $this->processForm( $item );
    }

    public function putAction( $id ) {
        $item = $this->getPricebookManager()->findItem( $id );
        return $this->processForm( $item );
    }

    /**
     * 
     * @param \TC\ApiBundle\Entity\PricebookItem $item
     */
    private function processForm( PricebookItem $item ) {
        $statusCode = $item->getId() ? 204 : 201;

        $formType = new PricebookItemType();
        $form = $this->createForm( $formType, $item );
        $form->handleRequest( $this->getRequest() );
        /* @var $view View */
        $view = View::create();
        if ( $form->isValid() ) {
            $this->getPricebookManager()->saveItem( $item );

            $view->setStatusCode( $statusCode );
            $view->setData( $item );
            $view->setSerializationContext( $this->getContext( array("pricebook") ));
        } else {
            $view->setData( $form );
            $view->setStatusCode( 400 );
        }

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
