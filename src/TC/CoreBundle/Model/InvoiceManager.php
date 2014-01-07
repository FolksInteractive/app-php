<?php

namespace TC\CoreBundle\Model;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Deliverable;
use TC\CoreBundle\Entity\Invoice;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

/**
 * RelationManager
 *
 * @author Francis Poirier
 */
class InvoiceManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var RelationManager $wm 
     */
    protected $rm;

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
    public function __construct( EntityManager $em, RelationManager $rm, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->rm = $rm;
        $this->validator = $validator;
    }
        
    /**
     * 
     * @param Relation $relation
     * @return ArrayCollection
     */
    public function findAllByRelation( Relation $relation ){
        return $relation->getInvoices();
    }

    /**
     * 
     * @param Relation $relation
     * @param integer $id
     * @return Invoice
     * @throws NotFoundHttpException
     */
    public function findByRelation( Relation $relation, $id ) {
        try {
            $invoice = $this->em->getRepository( "TCCoreBundle:Invoice" )
                    ->createQueryBuilder( "b" )
                    ->where( "b.relation = :relation" )
                    ->andWhere( "b.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "relation", $relation )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Invoice not found' );
        }
        
        return $invoice;
    }
    
    /**
     * 
     * @param Relation $relation
     * @return Invoice
     */
    public function create( Relation $relation ) {
        $invoice = new Invoice();
        $invoice->setRelation( $relation );
        $invoice->setIssuedAt( new DateTime() );  
        $invoice->setDueAt( new DateTime("1 month") );
        
        // Find the next invoice number based on the last invoice invoiced
        $no = 0;
        
        if( $invoice->getRelation()->getInvoices()->count() > 0 )
            $no = $invoice->getRelation()->getInvoices()->last()->getNo();
        
        $no++;
        
        $invoice->setNo($no);
        $invoice->setRelation($relation);
        
        return $invoice;
    }
    
    public function addDeliverable( Invoice $invoice, Deliverable $deliverable ){
        $deliverable->setInvoiced(true);
        $invoice->addDeliverable($deliverable);
        
        return $invoice;
    }

    /**
     * 
     * @param Invoice $invoice
     */
    public function save( Invoice $invoice ) {
        
        if( !$this->isValid($invoice) )
            return false;

        $this->em->persist( $invoice );
        $this->em->flush();
    }

    /**
     * 
     * @param Invoice $invoice
     */
    public function remove( Invoice $invoice ) {
        $this->em->remove( $invoice );
        $this->em->flush();
    }
    
    /**
     * 
     * @param $invoice
     * @return boolean
     */
    public function isValid( Invoice $invoice, &$errors = null ) {
        // Make sure order is valid before saving
        $errors = $this->validator->validate( $invoice );
        
        return ($errors->count() <= 0);
    }

    /**
     * 
     * @param Invoice $invoice
     */
    public function isEditable( Invoice $invoice ){
        // Only the vendor can edit a Invoice
        if( $this->workspace != $invoice->getRelation()->getVendor() )
            return false;
        
        return true;
    }
}

?>
