<?php

namespace TC\ApiBundle\Controller;

use TC\ApiBundle\Form\ContactType;
use TC\ApiBundle\Entity\Contact;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;

/**
 * @RouteResource("Contact")
 */
class ContactsController extends Controller {

    public function cgetAction() {
        $contactList = $this->getContactManager()->getContactLists();

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $contactList );
        $view->setSerializationContext( $this->getContext( array("contacts") ));
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getAction( $id ) {
        $contact = $this->getContactManager()->findContact( $id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $contact );
        $view->setSerializationContext( $this->getContext( array("contact") ));
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function postAction() {
        $contact = $this->getContactManager()->createContact();
        return $this->processForm( $contact );
    }

    public function putAction( $id ) {
        $contact = $this->getContactManager()->findContact( $id );
        return $this->processForm( $contact );
    }

    /**
     * 
     * @param \TC\ApiBundle\Entity\Relation $contact
     */
    private function processForm( Contact $contact ) {
        $statusCode = $contact->getId() ? 204 : 201;

        $formType = new ContactType();
        $form = $this->createForm( $formType, $contact );
        $form->handleRequest( $this->getRequest() );
        /* @var $view View */
        $view = View::create();
        if ( $form->isValid() ) {
            $this->getContactManager()->saveContact( $contact );

            $view->setStatusCode( $statusCode );
            $view->setData( $contact );
            $view->setSerializationContext( $this->getContext( array("contact") ));
        } else {
            $view->setData( $form );
            $view->setStatusCode( 400 );
        }

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    /**
     * @return ContactManager
     */
    private function getContactManager() {
        return $this->container->get( "tc.manager.contact" );
    }
    
    private function getContext( $groups ){
        $context = new SerializationContext();
        $context->setVersion("0");
        $context->setGroups($groups);
        
        return $context;
    }
}

?>
