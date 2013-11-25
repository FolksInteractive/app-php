<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Comment;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;
/**
 * ThreadManager
 *
 * @author Francis Poirier
 */
class ThreadManager {
    
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
    public function __construct( EntityManager $em, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        
        $this->user = $securityContext->getToken()->getUser();
        
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->validator = $validator;
    }
    /**
     * 
     * @param integer $id
     * @return Thread
     * @throws NotFoundHttpException
     */
    public function find( $id ) {
        try {
            /** @var Thread */
            $thread = $this->em->getRepository( "TCCoreBundle:Thread" )
                    ->createQueryBuilder( "t" )
                    ->where( ":workspace MEMBER OF t.followers" )
                    ->andWhere( "t.id = :id" )
                    ->setParameter( "id", $id )
                    ->setParameter( "workspace", $this->workspace )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Thread not found' );
        }
        return $thread;
    }
    
    /**
     * 
     * @param Thread Thread $thread
     * @return Comment
     */
    public function createComment( Thread $thread ) {
        $comment = new Comment();
        $comment->setAuthor( $this->workspace );
        $comment->setThread( $thread );

        return $comment;
    }

    /**
     * 
     * @param Comment $comment
     */
    public function saveComment( Comment $comment ) {
        /**
         * @todo Send notification
         */
        $this->em->persist( $comment );
        $this->em->flush();
    }
}

?>
