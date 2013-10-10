<?php

namespace TC\CoreBundle\Model;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Mailer\Mailer;
use TC\UserBundle\Entity\User;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Validator\Validator;

/**
 * RelationManager
 *
 * @author Francis Poirier
 */
class BillManager {

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

}

?>
