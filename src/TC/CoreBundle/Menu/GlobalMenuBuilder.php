<?php

namespace TC\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\Request;

class GlobalMenuBuilder extends ContainerAware {

    /**
     * Build the main menu
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function globalMenu( FactoryInterface $factory, array $options ) {
        /**
         * @var Request $request
         */
        $request = $this->container->get("request");
        
        $menu = $factory->createItem( 'root' );
        $menu->setChildrenAttribute( "class", "tc-global-menu tc-menu" );
        
        // Account Settings
        $menu
            ->addChild( 'Account Settings', array(
                'route' => 'fos_user_profile_edit'
            ) )
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-cog' );
                
        // Leave feedback
        $menu
            ->addChild( 'Leave your feedback', array('uri' => '#'))                
            ->setAttribute( "data-toggle", "modal" )                
            ->setAttribute( "data-target", "#feedbackModal" )        
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-send' );

        // Sign out
        $menu
            ->addChild( 'Sign out', array(
                'route' => 'fos_user_security_logout'
            ) )
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-log-out' );
            
        return $menu;
    }
    /**
     * Build the main menu
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function mainMenu( FactoryInterface $factory, array $options ) {

        $menu = $factory->createItem( 'root' );
        $menu->setChildrenAttribute( "class", "tc-main-menu tc-menu" );

        $menu
            ->addChild( 'Dashboard', array(
                'route' => 'dashboard'
            ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-dashboard" )
            ->setExtra( "sub_label", "Bird eye view on what's going on" );

        $menu
            ->addChild("Work")
            ->setAttribute('class', 'tc-menu-header');
        
        // Service Providers
        $menu
            ->addChild( 'Service Providers', array(
                'route' => 'vendor_index'
            ) )
            ->setExtra( "icon_classes", "icon-relations" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-relations-dark-lg" )
            ->setExtra( "sub_label", "Build & manage relationships" )
            
            ->setExtra( "routes", array(
                array( "route" => 'vendor_overview' ),
                array( "route" => 'vendor_new' ),
                array( "route" => 'vendor_create' )
            ));
        /*
            // New Relation
            ->addChild('New Service Provider', array(
                'route' => 'vendor_new'
            ));
        */

        // Projects
        $menu
            ->addChild( 'Projects', array(
                'route' => 'project'
            ) )
            ->setExtra( "icon_classes", "icon-projects" )
            ->setExtra( "sub_label", "Manage multiple service providers in the same project" );

        $menu
            ->addChild("Hire")
            ->setAttribute('class', 'tc-menu-header');
        
        $menu
            ->addChild( 'Clients', array(
                'route' => 'client_index'
            ) )
            ->setExtra( "icon_classes", "icon-relations" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-relations-dark-lg" )
            ->setExtra( "sub_label", "Build & manage relationships" )
            
            ->setExtra( "routes", array(
                array( "route" => 'client_overview' ),
                array( "route" => 'client_new' ),
                array( "route" => 'client_create' )
            ));
        /*
            // New Relation
            ->addChild('New Client', array(
                'route' => 'client_new'
            ));
         */  
        $menu
            ->addChild( 'Price Book', array(
                'route' => 'pricebook'
            ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-book" )
            ->setExtra( "sub_label", "Your pricing catalog" );
        
        return $menu;
    }
    
}

?>
