<?php
namespace TC\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\CoreBundle\Model\OrderManager;
use TC\CoreBundle\Model\RelationManager;
use TC\CoreBundle\Model\WorkspaceManager;
use TC\UserBundle\Entity\User;
use TC\UserBundle\Model\UserManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class LoadUserData implements FixtureInterface, ContainerAwareInterface
{
    /*
     * @var $container ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        
        /**
         * @var UserManager $um
         */
        $um = $this->container->get("tc_user.manager.user");
        
        /**
         * @var User $user
         */
        $user = $um->createUser();
        $user->setEmail("fpoirier@flsolutions.ca");
        $user->setPlainPassword(123);
        $user->setEnabled(true);
        $user->setFirstName("Francis");
        $user->setLastName("Poirier");
        /**
         * @var Workspace $meWorkspace
         */
        $workspace = $this->container->get("tc.manager.workspace")->createWorkspace($user);
        $user->setWorkspace($workspace);
        
        $this->container->get("doctrine.orm.entity_manager")->persist($workspace);
        $this->container->get("doctrine.orm.entity_manager")->flush();
        
        
        /**
         * @var User $user
         */
        $user = $um->createUser();
        $user->setEmail("fpoirier@gmail.com");
        $user->setPlainPassword(123);
        $user->setEnabled(true);
        $user->setFirstName("Francis");
        $user->setLastName("GMail");
        /**
         * @var Workspace $meWorkspace
         */
        $workspace = $this->container->get("tc.manager.workspace")->createWorkspace($user);
        $user->setWorkspace($workspace);
        
        $this->container->get("doctrine.orm.entity_manager")->persist($workspace);
        $this->container->get("doctrine.orm.entity_manager")->flush();
        
    }
}
?>
