<?php

namespace TC\CoreBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernel;
use Symfony\Component\HttpKernel\KernelEvents;

class RememberListener implements EventSubscriberInterface {

    public static function getSubscribedEvents(){
        return array(
            KernelEvents::RESPONSE => 'onKernelResponse'
        );
    }

    public function onKernelResponse( FilterResponseEvent $event ){
        if( HttpKernel::MASTER_REQUEST == $event->getRequestType() ){

            $response = $event->getResponse();
            $request = $event->getRequest();
            $route = $request->get( '_route' );
            
            $remember = array();
            if( $request->cookies->has( 'remember' ) )
                $remember = json_decode( $request->cookies->get( 'remember' ), true );

            if( $route ){
                if( strpos( $route, 'vendor_' ) === 0 ){
                    $remember[ 'was' ] = 'vendor'; // was a client     
                }else if( strpos( $route, 'client_' ) === 0 ){
                    $remember[ 'was' ] = 'client'; // was a vendor
                }
            }

            $cookie = new Cookie( 'remember', json_encode( $remember ) );
            $response->headers->setCookie( $cookie );
            $event->setResponse( $response );
        }
    }

}
