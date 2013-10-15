<?php

namespace TC\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use TC\UserBundle\Model\UserManager;

class RegistrationController extends BaseController {

    public function registerAction( Request $request ) {
        /**
         * @var FactoryInterface $formFactory
         */
        $formFactory = $this->container->get( 'fos_user.registration.form.factory' );
        /**
         * @var UserManager $userManager 
         */
        $userManager = $this->container->get( 'fos_user.user_manager' );
        /**
         * @var EventDispatcherInterface  $dispatcher
         */
        $dispatcher = $this->container->get( 'event_dispatcher' );

        $form = $formFactory->createForm();

        $user = $userManager->createUser();
        
        // Before setting Data to form when it is submitted, we must make 
        // sure that there is not a disabled user for the same email/username.
        // Disabled user can be created when someone invites somebody else to 
        // a relation and there is no user for the specified email.
        if ( 'POST' === $request->getMethod() && $request->request->has( $form->getName() ) ) {

            $params = $request->request->get( $form->getName() );

            if ( isset($params["email"]) ) {

                if ( null != $tempUser = $userManager->findUserByEmail( $params["email"] ) )
                    $user = $tempUser;
            }
        }

        $user->setEnabled( true );


        $event = new GetResponseUserEvent( $user, $request );
        $dispatcher->dispatch( FOSUserEvents::REGISTRATION_INITIALIZE, $event );

        if ( null !== $event->getResponse() ) {
            return $event->getResponse();
        }


        $form->setData( $user );

        if ( 'POST' === $request->getMethod() ) {
            $form->bind( $request );

            if ( $form->isValid() ) {
                $event = new FormEvent( $form, $request );
                $dispatcher->dispatch( FOSUserEvents::REGISTRATION_SUCCESS, $event );

                $userManager->updateUser( $user );

                if ( null === $response = $event->getResponse() ) {
                    $url = $this->container->get( 'router' )->generate( 'fos_user_registration_confirmed' );
                    $response = new RedirectResponse( $url );
                }

                $dispatcher->dispatch( FOSUserEvents::REGISTRATION_COMPLETED, new FilterUserResponseEvent( $user, $request, $response ) );

                return $response;
            }
        }

        return $this->container->get( 'templating' )->renderResponse( 'FOSUserBundle:Registration:register.html.' . $this->getEngine(), array(
                    'form' => $form->createView(),
        ) );
    }

}
