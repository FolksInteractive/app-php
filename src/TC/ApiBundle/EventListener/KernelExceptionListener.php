<?php

namespace TC\ApiBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

class KernelExceptionListener {

    public function onKernelException( GetResponseForExceptionEvent $event ) {
        // get exception
        $exception = $event->getException();
        // get path
        $path = $event->getRequest()->getPathInfo();
        
        if ( $exception instanceOf AuthenticationException && ($event->getRequest()->isXmlHttpRequest() || strpos( $path, '/api' ) === 0) ) {
            $response = new Response();
            $response->setStatusCode( 401 );
            $event->setResponse( $response );
            $event->stopPropagation();
        }
    }

}

?>
