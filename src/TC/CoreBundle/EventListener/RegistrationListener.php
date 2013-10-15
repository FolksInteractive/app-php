<?php

namespace TC\CoreBundle\EventListener;

use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use TC\CoreBundle\Entity\Workspace;
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
        /**
         * @var User $user
         */
        $user = $event->getForm()->getData();
        
        if(!$user->getWorkspace()){
            $workspace = new Workspace();
            $workspace->setUser($user);
        }
    }

}
