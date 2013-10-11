<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Workspace;
use TC\UserBundle\Entity\User;
use TC\UserBundle\Model\UserManager;

/**
 * WorkspaceManager
 *
 * @author Francis Poirier
 */
class WorkspaceManager {

    /** @var EntityManager $em  */
    protected $em;
    
    /** @var UserManager */
    protected $um;
    
    /** @var User $user */
    protected $user;
    
    /** @var Workspace $workspace */
    protected $workspace;

    /** @var Validator $validator */
    protected $validator;
    
    /**
     * 
     * @param EntityManager $em
     * @param SecurityContext $securityContext
     * @throws type
     */
    public function __construct( EntityManager $em, UserManager $um, SecurityContext $securityContext, Validator $validator) {

        $this->em = $em;
        $this->um = $um;
        $this->validator = $validator;
    }

    /**
     * 
     * @param User $user
     * @return Workspace
     */
    public function createWorkspace( User $user ) {
        $workspace = new Workspace();
        $workspace->setUser( $user );
        $user->setWorkspace($workspace);
        return $workspace;
    }

    public function find( $slug ) {
        $workspace = null;

        if ( $slug instanceof Workspace ) {
            $workspace = $slug;
        } else

        // If the id is passed
        if ( is_int( $slug ) ) {
            $workspace = $this->em->getRepository( "TCCoreBundle:Workspace" )->find( $slug );
            /**
             * @todo Make sure the workspace requested is part of the user's contact list
             *  ->createQueryBuilder("w")
              ->where("w.id = ?slug")
              ->setParameter( "slug", $slug )
              ->andWhere( "w MEMBER OF w.contactList.contacts")
              ->getFirstResult();
             */
            if ( $workspace == null )
                throw new InvalidArgumentException( "Workspace could not be found." );
        }else

        // If it is string should be an email
        if ( is_string( $slug ) ) {
            $user = $this->um->findUserByEmail( $slug );
            if ( $user )
                $workspace = $this->um->findUserByEmail( $slug )->getWorkspace();
        }

        return $workspace;
    }
    
    public function saveWorkspace( Workspace $workspace ){
        // Make sure Workspace is valid before saving
        $errors = $this->validator->validate( $workspace );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $workspace );
        $this->em->flush();
    }

}

?>
