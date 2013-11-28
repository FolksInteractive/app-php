<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Bill;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

/**
 * RelationManager
 *
 * @author Francis Poirier
 */
class RelationManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var WorkspaceManager $wm 
     */
    protected $wm;

    /**
     * @var User $user
     */
    protected $user;

    /**
     * @var Workspace $workspace
     */
    protected $workspace;

    /**
     * @var Mailer $mailer
     */
    protected $mailer;

    /**
     * @var Validator $validator
     */
    protected $validator;

    /**
     * Constructor
     * 
     * @param EntityManager $em
     */
    public function __construct( EntityManager $em, WorkspaceManager $wm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->wm = $wm;
        $this->validator = $validator;
    }

    /**
     * 
     * @return Collection
     */
    public function findAllByClient() {
        return $this->workspace->getClientRelations();
    }

    /**
     * 
     * @return Collection
     */
    public function findAllByVendor() {
        return $this->workspace->getVendorRelations();
    }

    /**
     * 
     * @param integer $id
     * @return Relation
     * @throws NotFoundHttpException
     */
    public function find( $id ) {
        try {
            /* @var $relation Relation */
            $relation = $this->em->getRepository( "TCCoreBundle:Relation" )->createQueryBuilder( "r" )
                    ->where( "r.vendor = :workspace" )
                    ->orWhere( "r.client = :workspace" )
                    ->andWhere( "r.id = :id" )
                    ->andWhere( "r.active = true" )
                    ->setParameter( "id", $id )
                    ->setParameter( "workspace", $this->workspace )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Relation not found' );
        }
        return $relation;
    }

    /**
     * 
     * @param integer $id
     * @return Relation
     * @throws NotFoundHttpException
     */
    public function findByClient( $id ) {
        try {
            /* @var $relation Relation */
            $relation = $this->em->getRepository( "TCCoreBundle:Relation" )->createQueryBuilder( "r" )
                    ->where( "r.client = :workspace" )
                    ->andWhere( "r.id = :id" )
                    ->andWhere( "r.active = true" )
                    ->setParameter( "id", $id )
                    ->setParameter( "workspace", $this->workspace )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Relation not found' );
        }
        return $relation;
    }

    /**
     * 
     * @param integer $id
     * @return Relation
     * @throws NotFoundHttpException
     */
    public function findByVendor( $id ) {
        try {
            /* @var $relation Relation */
            $relation = $this->em->getRepository( "TCCoreBundle:Relation" )->createQueryBuilder( "r" )
                    ->where( "r.vendor = :workspace" )
                    ->andWhere( "r.id = :id" )
                    ->andWhere( "r.active = true" )
                    ->setParameter( "id", $id )
                    ->setParameter( "workspace", $this->workspace )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Relation not found' );
        }
        return $relation;
    }

    /**
     * @return Relation
     */
    public function createForClient() {
        $relation = $this->create();
        $relation->setClient( $this->workspace );
        
        return $relation;
    }

    /**
     * @return Relation
     */
    public function createForVendor() {
        $relation = $this->create();
        $relation->setVendor( $this->workspace );
        
        return $relation;
    }

    /**
     * @return Relation
     */
    private function create() {
        $relation = new Relation();
        $relation->setCreator( $this->workspace );
        return $relation;
    }

    /**
     * 
     * @param Relation $relation
     */
    public function save( Relation $relation, $sendInvitation = true ) {
        $isNew = ($relation->getId());
        // Make sure Relation is valid before saving
        $errors = $this->validator->validate( $relation );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $relation );
        $this->em->flush();
        
        // If Relation is new send a invitation email to the other partie
        if ( !$isNew && $sendInvitation )
            $this->sendInvitation( $relation );
    }

    /**
     * 
     * @param Relation $relation
     */
    private function sendInvitation( Relation $relation ) {

        // if user is vendor send client invitation
        if ( $relation->getVendor() && $relation->getVendor() == $this->workspace ) {
            $this->mailer->sendClientInvitation( $relation );
            return;
        }
        
        if ( $relation->getClient() && $relation->getClient() == $this->workspace ) {            
            $this->mailer->sendVendorInvitation( $relation );
            return;
        }
    }

    /**
     * 
     * @param Relation $relation
     * @throws AccessDeniedException
     */
    public function archive( Relation $relation ) {
        if ( $relation->getCreator() == $this->workspace ) {
            $relation->setActive( false );
        } else {
            throw new AccessDeniedException( "You must be the creator of the relation to remove it." );
        }
    }

}
?>
