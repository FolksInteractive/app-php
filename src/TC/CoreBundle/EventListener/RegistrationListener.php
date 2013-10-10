<?php

namespace TC\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TC\CoreBundle\Entity\Enrollment;
use TC\CoreBundle\Model\WorkspaceManager;
use TC\UserBundle\Entity\User;

class RegistrationListener implements EventSubscriberInterface {
    
    /** @var EntityManager $em  */
    protected $em;
    
    /* @var $wm WorkspaceManager */
    protected $wm;

    public function __construct( EntityManager $em, WorkspaceManager $wm ) {
        $this->em = $em;
        $this->wm = $wm;
    }

    public static function getSubscribedEvents() {
        return array(
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }

    public function onRegistrationSuccess( FormEvent $event ) {
        /** @var User $user */
        $user = $event->getForm()->getData();

        // Create a workspace to the new User
        $workspace = $this->wm->createWorkspace( $user );
        $this->wm->saveWorkspace( $workspace );

        /**
         * @todo Create a EnrollmentManager with findEnrollment($email), saveEnrollment() and assignWorkspace(()
         */
        // Finds existing enrollments for user email and assign workspace to those enrollments        
        $enrollments = $this->em->getRepository( 'TCCoreBundle:Enrollment' )->findByEmail( $user->getEmail() );

        foreach ( $enrollments as $key => $enrollment ) {
            $enrollment->setWorkspace( $workspace );
            $this->em->persist( $enrollment );
        }
        $this->em->flush();
    }

}
