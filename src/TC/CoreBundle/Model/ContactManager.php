<?php

namespace TC\CoreBundle\Model;

use TC\UserBundle\Entity\User;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Entity\Contact;
use TC\CoreBundle\Entity\ContactList;
use TC\CoreBundle\Entity\ContactTag;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * ContactManager
 *
 * @author Francis Poirier
 */
class ContactManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var User $user
     */
    protected $user;
    
    /**
     * @var Workspace $workspace
     */
    protected $workspace;

    /**
     * @var ContactList $contactList
     */
    protected $contactList;

    /**
     * @var ContactRepository $repo
     */
    protected $repo;

    /**
     * Constructor
     * 
     * @param \Doctrine\ORM\EntityManager $em
     */
    public function __construct( EntityManager $em, SecurityContext $securityContext ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new \InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        
        $this->contactList = $this->workspace->getContactList();
        
        $this->em = $em;

        $this->repo = $em->getRepository( "TCCoreBundle:Contact" );
    }
    
    /**
     * 
     * @return \TC\CoreBundle\Entity\ContactList
     */
    public function findAll(){
        return $this->contactList;
    }

    /**
     * 
     * @param string $email
     * @return Doctrine\Common\Collections\Collection 
     */
    public function findByEmail( $email ) {
        return $this->repo->findBy( array("email" => $email) );
    }
    
    /**
     * 
     * @param int $id Contact's id
     * @return Contact
     * @throws NotFoundHttpException
     */
    public function find( $id ) {
        try {
            /* @var $contact Contact */
            $contact = $this->repo->createQueryBuilder( "c" )
                    ->where( "c.contactList = :contactList" )
                    ->andWhere( "c.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "contactList", $this->contactList )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Contact not found' );
        }
        return $contact;
    }

    /**
     * 
     * @param \TC\CoreBundle\Entity\ContactList $contactList
     * @return \TC\CoreBundle\Entity\Contact
     */
    public function create( ) {
        $contact = new Contact();
        $contact->setContactList( $this->contactList );

        return $contact;
    }

    /**
     * 
     * @param \TC\CoreBundle\Entity\Contact $contact
     */
    public function save( Contact $contact ) {
        $this->em->persist( $contact );
        $this->em->flush();
    }

    /**
     * 
     * @param \TC\CoreBundle\Entity\Contact $contact
     */
    public function remove( Contact $contact ) {
        $this->em->remove( $contact );
        $this->em->flush();
    }
}

?>
