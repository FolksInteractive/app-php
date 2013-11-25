<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Validator;
use TC\CoreBundle\Entity\Project;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;

/**
 * ProjectManager
 *
 * @author Francis Poirier
 */
class ProjectManager {

    /**
     * @var EntityManager $em 
     */
    protected $em;

    /**
     * @var WorkspaceManager $wm 
     */
    protected $wm;

    /**
     * @var OrderManager $wm 
     */
    protected $om;

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
    public function __construct( EntityManager $em, WorkspaceManager $wm, OrderManager $om, SecurityContext $securityContext, Mailer $mailer, Validator $validator ) {
        $this->user = $securityContext->getToken()->getUser();
        if ( !$this->user instanceof User ) {
            throw new InvalidArgumentException();
        }

        $this->workspace = $this->user->getWorkspace();
        $this->mailer = $mailer;
        $this->em = $em;
        $this->wm = $wm;
        $this->om = $om;
        $this->validator = $validator;
    }

    /**
     * 
     * @return Collection
     */
    public function findAll() {
        return  $this->workspace->getProjects();
    }

    /**
     * 
     * @param integer $id
     * @return Project
     * @throws NotFoundHttpException
     */
    public function find( $id ) {
        try {
            /* @var $relation Relation */
            $relation = $this->em->getRepository( "TCCoreBundle:Project" )->createQueryBuilder( "p" )
                    ->andWhere( "p.id = :id" )
                    ->andWhere( "p.active = true")
                    ->andWhere( "p.workspace = :workspace" )
                    ->setParameter( "id", $id )
                    ->setParameter( "workspace", $this->workspace )
                    ->getQuery()
                    ->getSingleResult();
        } catch ( NoResultException $e ) {
            throw new NotFoundHttpException( 'Project not found' );
        }
        return $relation;
    }
    
    /**
     * @return Project
     */
    public function create() {
        $project = new Project();
        $project->setWorkspace( $this->workspace );
                
        return $project;
    }

    /**
     * 
     * @param Project $project
     */
    public function save( Project $relation ) {
        // Make sure Project is valid before saving
        $errors = $this->validator->validate( $project );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );


        $this->em->persist( $project );
        $this->em->flush();
    }

    /**
     * 
     * @param Project $project
     * @throws AccessDeniedException
     */
    public function archive( Project $project ) {
        if ( $project->getWorkspace() == $this->workspace ) {
            $project->setActive(false);
        } else {
            throw new AccessDeniedException( "You must be the owner of the project to remove it." );
        }
    }


}

?>
