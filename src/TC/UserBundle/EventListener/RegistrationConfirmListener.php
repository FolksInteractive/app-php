<?php

namespace TC\UserBundle\EventListener;

use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationConfirmListener implements EventSubscriberInterface {

    /**
     * @var UrlGeneratorInterface
     */
    private $router;

    /**
     * @var Session 
     */
    private $session;

    public function __construct( UrlGeneratorInterface $router, Session $session ){
        $this->router = $router;
        $this->session = $session;
    }

    public static function getSubscribedEvents(){
        return array(
            FOSUserEvents::REGISTRATION_CONFIRM => 'onConfirm',
        );
    }

    public function onConfirm( GetResponseUserEvent $event ){
        if( $this->session->has( '_security.secured_area.target_path' ) ){
            $url = $this->session->get( '_security.secured_area.target_path' );
        }else{
            $url = $this->router->generate( 'dashboard' );
        }

        $event->setResponse( new RedirectResponse( $url ) );
    }

}
