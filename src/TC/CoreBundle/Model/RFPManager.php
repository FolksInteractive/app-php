<?php

namespace TC\CoreBundle\Model;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\RFP;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

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
    public function __construct( EntityManager $em, RelationManager $pm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ){
        $this->user = $securityContext->getToken()->getUser();
        if( !$this->user instanceof User ){
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
    public function findAllByRelation( Relation $relation ){
        return $relation->getRFPs();
    }

    /**
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllByClient( Relation $relation ){
        $rfps = $this->em->getRepository( "TCCoreBundle:RFP" )
            ->createQueryBuilder( "r" )
            ->leftJoin( "TCCoreBundle:Order", "o", "WITH", "o.rfp = r" )
            ->where( "r.relation = :relation" )
            ->andWhere( "r.ready = true" )
            ->setParameter( "relation", $relation )
            ->getQuery()
            ->getResult();

        return $rfps;
    }

    /**
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllUnproposedByRelation( Relation $relation ){
        $ordersQB = $this->em->createQueryBuilder();
        
        $ordersQB
            ->select( 'DISTINCT IDENTITY(o.rfp)' )
            ->from( "TCCoreBundle:Order", "o" )
            ->join("TCCoreBundle:RFP", '_r', 'WITH', '_r = o.rfp')
            ->where( "o.rfp IS NOT NULL" )
            ->andWhere( "r.relation = :relation" );

        $rfpsQB = $this->em->getRepository( "TCCoreBundle:RFP" )->createQueryBuilder( "r" );

        $rfps = $rfpsQB
            ->where( "r.relation = :relation" )
            ->andWhere( "r.ready = true" )
            ->andWhere( $rfpsQB->expr()->notIn( "r.id", $ordersQB->getDQL() ) )
            ->setParameter( "relation", $relation )
            ->getQuery()
            ->getResult();
        
        return $rfps;
    }

    /**
     * 
     * @param Relation $relation
     * @return Collection 
     */
    public function findAllByVendor( Relation $relation ){
        $rfps = $this->em->getRepository( "TCCoreBundle:RFP" )
            ->createQueryBuilder( "r" )
            ->leftJoin( "TCCoreBundle:Order", "o", "WITH", "o.rfp = r" )
            ->where( "r.relation = :relation" )
            ->setParameter( "relation", $relation )
            ->getQuery()
            ->getResult();

        return $rfps;
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return RFP
     * @throws NotFoundHttpException
     */
    public function findByRelation( $relation, $id ){
        try{
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
        } catch( NoResultException $e ){
            throw new NotFoundHttpException( 'RFP not found' );
        }

        // If the rfp is not ready the vendor can't read it
        if( $this->workspace == $rfp->getRelation()->getVendor() && !$rfp->isReady() )
            throw new NotFoundHttpException( 'RFP not found' );

        return $rfp;
    }

    /**
     * 
     * @param Relation $relation
     * @return RFP
     */
    public function create( Relation $relation ){
        $rfp = new RFP();
        $rfp->setCreator( $this->workspace );
        $rfp->setRelation( $relation );

        $thread = new Thread();
        $thread->addFollower( $rfp->getRelation()->getVendor() );
        $thread->addFollower( $rfp->getRelation()->getClient() );
        $rfp->setThread( $thread );
        /**
         * @todo Send notification
         */
        return $rfp;
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function ready( RFP $rfp ){

        if( !$this->isSendable( $rfp ) )
            return false;

        $rfp->setReady( true );

        $this->mailer->sendRFPReadyNotification( $rfp );
    }

    /**
     * 
     * @param RFP $rfp
     * @param object $cancellation See Client/RFPController::createCancelForm
     */
    public function cancel( RFP $rfp, $cancellation = null ){

        if( !$this->isCancellable( $rfp ) )
            return false;

        $rfp->setCancelled( true );
        $rfp->setOrder( null );

        if( $rfp->getReady() )
            $this->mailer->sendRFPCancellation( $rfp, $cancellation );
    }

    /**
     * 
     * @param RFP $rfp
     * @param object $declinal See Vendor/RFPController::createDeclineForm
     */
    public function decline( RFP $rfp, $declinal = null ){

        if( !$this->isDeclinable( $rfp ) )
            return false;

        $rfp->setDeclined( true );
        $rfp->setOrder( null );
        $this->mailer->sendRFPDeclinal( $rfp, $declinal );
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function reopen( RFP $rfp ){
        if( !$this->isReopenable( $rfp ) )
            return false;

        $rfp->setReady(false);
        $rfp->setDeclined( false );
        $rfp->setCancelled( false );
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function save( RFP $rfp ){

        if( !$this->isValid( $rfp ) )
            return false;

        $this->em->persist( $rfp );
        $this->em->flush();
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function remove( RFP $rfp ){
        $this->em->remove( $rfp );
        $this->em->flush();
    }

    /**
     * 
     * @param $rfp
     * @return boolean
     */
    public function isValid( RFP $rfp, &$errors = null ){
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $rfp );

        return ($errors->count() <= 0);
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function isCancellable( RFP $rfp ){
        // Only the client can cancel a RFP
        if( $this->workspace != $rfp->getRelation()->getClient() )
            return false;

        // You can't cancel a RFP already cancelled
        if( $rfp->getCancelled() )
            return false;

        // You can'T cancel a RFP already declined by the vendor
        if( $rfp->getDeclined() )
            return false;

        return true;
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function isDeclinable( RFP $rfp ){
        // Only the vendor can cancel a RFP
        if( $this->workspace != $rfp->getRelation()->getVendor() )
            return false;

        // You can't decline a RFP already cancelled by the client
        if( $rfp->getCancelled() )
            return false;

        // You can't decline a RFP already declined
        if( $rfp->getDeclined() )
            return false;

        // You can't decline a RFP in draft mode
        if( !$rfp->getReady() )
            return false;

        return true;
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function isEditable( RFP $rfp ){
        // Only the client can edit a RFP
        if( $this->workspace != $rfp->getRelation()->getClient() )
            return false;

        // You can't edit a RFP cancelled
        if( $rfp->getCancelled() )
            return false;

        // You can't edit a RFP declined
        if( $rfp->getDeclined() )
            return false;

        return true;
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function isSendable( RFP $rfp ){
        // Only the client can send a RFP
        if( $this->workspace != $rfp->getRelation()->getClient() )
            return false;

        // You can't send a RFP cancelled
        if( $rfp->getCancelled() )
            return false;

        // You can't send a RFP declined
        if( $rfp->getDeclined() )
            return false;

        if( !$this->isValid( $rfp ) )
            return false;

        return true;
    }

    /**
     * 
     * @param RFP $rfp
     */
    public function isReopenable( RFP $rfp ){

        // Only a client can reopen a RFP
        if( $this->workspace == $rfp->getRelation()->getClient() ){
            if( $rfp->getCancelled() || $rfp->getDeclined() )
                return true;
        }

        return false;
    }

}

?>
