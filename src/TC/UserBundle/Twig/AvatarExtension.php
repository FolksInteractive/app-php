<?php

namespace TC\UserBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use TC\UserBundle\Entity\User;
use Twig_Extension;
use Twig_SimpleFunction;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AvatarExtension extends Twig_Extension {

    /**
     * @var ContainerInterface
     */
    private $container;
        
    public function __construct( ContainerInterface $container = null ){
        $this->container = $container;
    }
    
    public function getFunctions() {
        return array(
            new Twig_SimpleFunction( 'avatar',  array($this, 'getAvatar') ),
        );
    }
    
    public function getAvatar( User $user = null ){
        // If no user is specified we use the current user
        if( $user == null )
            $user = $this->getUser();
        
        if( $user->getAvatar() == null )
            return "/img/avatar.png";
        
        return $this->getUploaderHelper()->asset($user, "avatar");
    }
    
    /**
     * @return User
     */
    private function getUser() {
        return $this->container->get('security.context')->getToken()->getUser();
    }
    
    /**
     * @return string
     */
    public function getName() {
        return 'tc_user_avatar_extension';
    }
    
    /**
     * @return UploaderHelper
     */
    public function getUploaderHelper(){
        return $this->container->get('vich_uploader.templating.helper.uploader_helper');
    }
}

?>
