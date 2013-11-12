<?php

namespace TC\CoreBundle\Model;

use TC\UserBundle\Entity\User;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Entity\Pricebook;
use TC\CoreBundle\Entity\PricebookItem;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * PricebookManager
 *
 * @author Francis Poirier
 */
class PricebookManager {

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
     * @var Pricebook $priceBook
     */
    protected $priceBook;

    /**
     * @var PricebookRepository $repo
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
        
        $this->priceBook = $this->workspace->getPricebook();
        
        $this->em = $em;

        $this->repo = $em->getRepository( "TCCoreBundle:PricebookItem" );
    }
    
    /**
     * 
     * @return \TC\CoreBundle\Entity\Pricebook
     */
    public function getPricebook(){
        return $this->priceBook;
    }
    
    /**
     * 
     * @param int $id PricebookItem's id
     * @return PricebookItem
     * @throws NotFoundHttpException
     */
    public function findItem( $id ) {
        try {
            /* @var $item PricebookItem */
            $item = $this->repo->createQueryBuilder( "i" )
                    ->where( "i.priceBook = :priceBook" )
                    ->andWhere( "i.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "priceBook", $this->priceBook )
                    ->getQuery()
                    ->getSingleResult();
            
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Pricebook item not found' );
        }
        return $item;
    }

    /**
     * 
     * @return \TC\CoreBundle\Entity\PricebookItem
     */
    public function createItem( ) {
        $item = new PricebookItem();
        $item->setPricebook( $this->priceBook );

        return $item;
    }

    /**
     * 
     * @param \TC\CoreBundle\Entity\PricebookItem $item
     */
    public function saveItem( PricebookItem $item ) {
        $this->em->persist( $item );
        $this->em->flush();
    }

    /**
     * 
     * @param \TC\CoreBundle\Entity\PricebookItem $item
     */
    public function removeItem( PricebookItem $item ) {
        $this->em->remove( $item );
        $this->em->flush();
    }
}

?>
