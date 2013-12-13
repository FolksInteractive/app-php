<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Deliverable;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

/**
 * OrderManager
 *
 * @author Francis Poirier
 */
class DeliverableManager {

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
    protected $pm;

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
        $this->pm = $pm;
        $this->validator = $validator;
    }
    /**
     * 
     * @param Order $order
     * @return Deliverable
     */
    public function createDeliverable( Order $order ) {
        $deliverable = new Deliverable();
        $deliverable->setOrder( $order );
        $deliverable->setCreator( $this->workspace );
        
        return $deliverable;
    }

    /**
     * 
     * @param Order $order
     * @param integer $id
     * @return Deliverable
     * @throws NotFoundHttpException
     */
    public function findByOrder( Order $order, $id ) {
        try {
            $deliverable = $this->em->getRepository( "TCCoreBundle:Deliverable" )
                    ->createQueryBuilder( "d" )
                    ->where( "d.order = :order" )
                    ->andWhere( "d.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "order", $order )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Deliverable not found' );
        }
        return $deliverable;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return ArrayCollection
     * @throws NotFoundHttpException
     */
    public function findAllInProgressByRelation( Relation $relation ) {
        try {
            $deliverables = $this->em->getRepository( "TCCoreBundle:Deliverable" )
                    ->createQueryBuilder("d")
                    ->join("TCCoreBundle:Order", "o", "WITH", "o.relation = :relation")
                    ->where( "d MEMBER OF o.deliverables" )
                    ->andWhere("o.approved = true")
                    ->andWhere("d.completed = false")
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Deliverable not found' );
        }
        return $deliverables;
    }
    
    
    /**
     * 
     * @param Relation $relation
     * @return ArrayCollection
     * @throws NotFoundHttpException
     */
    public function findAllToBill( Relation $relation ) {
        try {
            $deliverables = $this->em->getRepository( "TCCoreBundle:Deliverable" )
                    ->createQueryBuilder("d")
                    ->join("TCCoreBundle:Order", "o", "WITH", "o.relation = :relation")
                    ->where( "d MEMBER OF o.deliverables" )
                    ->andWhere("o.approved = true")
                    ->andWhere("d.completed = true")
                    ->andWhere("d.billed = false")
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Deliverable not found' );
        }
        return $deliverables;
    }
    
    /**
     * 
     * @param Deliverable $deliverable
     * 
     * @throws AccessDeniedHttpException
     */
    public function complete(Deliverable $deliverable){
                
        //Only vendor can set a Deliverable to completed
        if($this->workspace != $deliverable->getOrder()->getRelation()->getVendor())
            throw new AccessDeniedHttpException();
        
        $deliverable->setCompleted(true);
    }

    /**
     * @param Deliverable $deliverable
     */
    public function save( Deliverable $deliverable ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $deliverable );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        $this->em->persist( $deliverable );
        $this->em->flush();
    }

    /**
     * 
     * @param Deliverable $order
     */
    public function remove( Deliverable $deliverable ) {
        $this->em->remove( $deliverable );
        $this->em->flush();
    }
}

?>
