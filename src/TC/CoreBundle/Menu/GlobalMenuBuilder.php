<?php

namespace TC\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;

class GlobalMenuBuilder extends ContainerAware {

    /**
     * Build the main menu
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function globalMenu( FactoryInterface $factory, array $options ) {

        $menu = $factory->createItem( 'root' );
        $menu->setChildrenAttribute( "class", "tc-global-menu tc-menu" );

        $menu
            ->addChild( 'Account Settings', array(
                'route' => 'vendor_dashboard'
        ) );

        $menu
            ->addChild( 'Leave your feedback', array(
            'route' => 'vendor_relation'
        ) );
        
        $menu
            ->addChild( 'Sign out', array(
                'route' => 'fos_user_security_log_out'
        ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-book" )
            ->setExtra( "sub_label", "Your pricing catalog" );
        
        return $menu;
    }
}

?>
