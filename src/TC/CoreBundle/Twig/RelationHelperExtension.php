<?php

namespace TC\CoreBundle\Twig;

use Symfony\Component\Security\Core\SecurityContext;
use TC\CoreBundle\Entity\Enrollment;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\Workspace;
use TC\UserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class RelationHelperExtension extends Twig_Extension {

    /**
     * @var SecurityContext 
     */
    private $securityContext;

    /**
     * @var UploaderHelper 
     */
    private $vichHelper;

    public function __construct( SecurityContext $securityContext, UploaderHelper $vichHelper ) {
        $this->securityContext = $securityContext;
        $this->vichHelper = $vichHelper;
    }

    public function getFunctions() {
        return array(
            new Twig_SimpleFunction( 'is_vendor', array($this, 'isVendor') ),
            new Twig_SimpleFunction( 'is_client', array($this, 'isClient') ),
            new Twig_SimpleFunction( 'is_creator', array($this, 'isCreator') ),
            new Twig_SimpleFunction( 'avatar', array($this, 'getAvatar') ),
        );
    }

    public function getAvatar( Enrollment $enrollment = null ) {
        if ( $enrollment && $enrollment->getWorkspace() ) {
            return $this->vichHelper->asset( $enrollment->getWorkspace()->getUser(), 'avatar' );
        }

        return "/img/users/user.png";
    }

    /**
     * 
     * @param Relation $relation
     * @param Workspace $workspace
     * @return boolean
     */
    public function isCreator( Relation $relation, Workspace $_workspace = null ) {
        $workspace = ($_workspace) ? $_workspace : $this->getWorkspace();
        return ($relation->getCreator() == $workspace );
    }

    /**
     * 
     * @param Relation $relation
     * @param Workspace $workspace
     * @return boolean
     */
    public function isVendor( Relation $relation, Workspace $_workspace = null ) {
        $workspace = ($_workspace) ? $_workspace : $this->getWorkspace();
        return ($relation->getVendor() == $workspace );
    }

    /**
     * 
     * @param Relation $relation
     * @param Workspace $workspace
     * @return boolean
     */
    public function isClient( Relation $relation, Workspace $_workspace = null ) {
        $workspace = ($_workspace) ? $_workspace : $this->getWorkspace();
        return ($relation->getClient() == $workspace );
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

    public function getName() {
        return 'tc_relation_helper';
    }

}

?>
