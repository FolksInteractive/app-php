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
            ->setChildrenAttribute( "data-toggle", "modal" )                
            ->setChildrenAttribute( "data-target", "#feedbackModal" )        
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-send' );

        $isClientMode = (strpos($request->attributes->get('_route') , "client_") === 0 );
        $isVendorMode = (strpos($request->attributes->get('_route') , "vendor_") === 0 );
        
        // Switch to vendor mode
        if( $isClientMode ){
            $menu
            ->addChild( 'Switch to Vendor mode', array(
                'route' => 'vendor_dashboard'
            ) )
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-retweet' );
        }
   
        // Switch to client mode
        if( $isVendorMode ){
            $menu
            ->addChild( 'Switch to Client mode', array(
                'route' => 'client_dashboard'
            ) )
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-retweet' );
        }        
        
        
        if( !$isClientMode && !$isVendorMode ){
            
            $menu
                ->addChild( 'Go to Client mode', array(
                    'route' => 'client_dashboard'
                ) )
                ->setExtra( 'icon_classes', 'glyphicon glyphicon-arrow-left' );
            
            $menu
                ->addChild( 'Go to Vendor mode', array(
                    'route' => 'vendor_dashboard'
                ) )
                ->setExtra( 'icon_classes', 'glyphicon glyphicon-arrow-left' );
        }
        
        // Sign out
        $menu
            ->addChild( 'Sign out', array(
                'route' => 'fos_user_security_logout'
            ) )
            ->setExtra( 'icon_classes', 'glyphicon glyphicon-log-out' );
            
        return $menu;
    }
}

?>
