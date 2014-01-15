<?php
namespace TC\CoreBundle\Reporter;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Workspace;
use TC\UserBundle\Entity\User;

abstract class AbstractReporter implements ReporterInterface {
    
    /**
     * @var EntityManager $em 
     */
    protected $em;
    
    /**
     * @var SecurityContext
     */
    private $securityContext;
    
    public function __construct( EntityManager $em, SecurityContext $securityContext ){
        
        $this->em = $em;
        
        $this->securityContext = $securityContext;
    }
        
    /**
     * @return User
     */
    private function getUser() {
        return $this->securityContext->getToken()->getUser();
    }

    /**
     * @return Workspace
     */
    private function getWorkspace() {
        return $this->getUser()->getWorkspace();
    }
}

?>
