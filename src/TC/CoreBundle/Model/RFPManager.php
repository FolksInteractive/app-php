<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;

/**
 * RFPManager
 *
 * @author Francis Poirier
 */
class RFPManager {

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
     *
     * @var RelationManager $pm
     */
    protected $rm;

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
    public function __construct( EntityManager $em, RelationManager $pm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->rm = $pm;
        $this->validator = $validator;
    }

    /**
     * 
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllByRelation( Relation $relation ) {
        return $relation->getRFPs();
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return RFP
     * @throws NotFoundHttpException
     */
    public function findByRelation( $relation, $id ) {
        try {
            /**
             *  @var RFP $rfp 
             */
            $rfp = $this->em->getRepository( "TCCoreBundle:RFP" )
                    ->createQueryBuilder( "r" )
                    ->where( "r.relation = :relation" )
                    ->andWhere( "r.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'RFP not found' );
        }
        
        // If the rfp is not ready the vendor can't read it
        if( $this->workspace == $rfp->getRelation()->getVendor() && !$rfp->isReady())
            throw new NotFoundHttpException( 'RFP not found' );
        
        return $rfp;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return RFP
     */
    public function create( Relation $relation ) {
        $rfp = new RFP();
        $rfp->setCreator( $this->workspace );
        $rfp->setRelation($relation);

        $thread = new Thread();
        $thread->addFollower( $rfp->getRelation()->getVendor());
        $thread->addFollower( $rfp->getRelation()->getClient());
        $rfp->setThread( $thread );
        /**
         * @todo Send notification
         */
        return $rfp;
    }

    public function ready( RFP $rfp ) {
        
        if ( $rfp->getRelation()->getClient() == $this->workspace ) {
            $rfp->setReady(true);
        }
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function save( RFP $rfp ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $rfp );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $rfp );
        $this->em->flush();
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function remove( RFP $rfp ) {
        $this->em->remove( $rfp );
        $this->em->flush();
    }
}

?>
