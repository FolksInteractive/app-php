<?php

namespace TC\UserBundle\Controller;

use Exception;
use FOS\UserBundle\Controller\SecurityController as BaseController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\CredentialsExpiredException;
use Symfony\Component\Security\Core\Exception\DisabledException;
use Symfony\Component\Security\Core\Exception\InsufficientAuthenticationException;
use Symfony\Component\Security\Core\Exception\LockedException;
use Symfony\Component\Security\Core\SecurityContext;

class SecurityController extends BaseController {

    public function loginAction( Request $request ){
        /** @var $session \Symfony\Component\HttpFoundation\Session\Session */
        $session = $request->getSession();

        // get the error if any (works with forward and redirect -- see below)
        if( $request->attributes->has( SecurityContext::AUTHENTICATION_ERROR ) ){
            $error = $request->attributes->get( SecurityContext::AUTHENTICATION_ERROR );
        }elseif( null !== $session && $session->has( SecurityContext::AUTHENTICATION_ERROR ) ){
            $error = $session->get( SecurityContext::AUTHENTICATION_ERROR );
            $session->remove( SecurityContext::AUTHENTICATION_ERROR );
        }else{
            $error = '';
        }

        if( $error )
            $error = $this->handleErrorMsg( $error );

        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get( SecurityContext::LAST_USERNAME );

        $csrfToken = $this->container->has( 'form.csrf_provider' ) ? $this->container->get( 'form.csrf_provider' )->generateCsrfToken( 'authenticate' ) : null;

        return $this->renderLogin( array(
                'last_username' => $lastUsername,
                'error'         => $error,
                'csrf_token'    => $csrfToken,
            ) );
    }

    private function handleErrorMsg( Exception $error ){
        if( $error instanceof AccountExpiredException ||
            $error instanceof LockedException ||
            $error instanceof BadCredentialsException ||
            $error instanceof CredentialsExpiredException ||
            $error instanceof DisabledException ||
            $error instanceof InsufficientAuthenticationException
         )
            return $error->getMessage();

        return "Opps something went wrong. Please try again later.";
    }

}
