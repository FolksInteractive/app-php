<?php

namespace TC\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;

class VendorMenuBuilder extends ContainerAware {

    /**
     * Build the main menu
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function mainMenu( FactoryInterface $factory, array $options ) {
        $user = $options['user'];

        $menu = $factory->createItem( 'root'/* , array('childrenAttributes' => array('id' => 'menu_id')) */ );
        $menu->setChildrenAttribute( "class", "tc-main-menu tc-menu" );
        //$menu->setUri($this->container->get('request')->getRequestUri());

        $menu
            ->addChild( '.icon-dashboard Dashboard', array(
                'route' => 'vendor_dashboard'
            ) )
            ->setExtra( "sub_label", "Bird eye view on what's going on" )
        ;

        $menu
            ->addChild( '.icon-relations Clients', array(
            'route' => 'vendor_relation'
            ) )
            ->setExtra( "sub_label", "Build & manage relationships" )
        ;
        
        $menu
            ->addChild( '.glyphicon-book Pricebook', array(
                'uri' => '#'
            ) )
            ->setExtra( "sub_label", "Your pricing catalog" )
        ;
        
        return $menu;
    }

    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem¸
     */
    public function relationMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $user = $options['user'];

        $routeParameters = array('idRelation' => $idRelation);

        $menu = $factory->createItem( 'root'/* , array('childrenAttributes' => array('id' => 'menu_id')) */ );
        $menu->setChildrenAttribute( "class", "tc-relation-menu tc-menu" );
        //$menu->setUri($this->container->get('request')->getRequestUri());

         $menu->addChild( '.icon-rfp-light Request For Proposal', array(
            'route' => 'vendor_relation_new',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "sub_label", "Describe & discuss your needs" )
        ;

        $menu->addChild( '.icon-orders Proposals', array(
            'route' => 'vendor_relation_orders',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "sub_label", "View, discuss & accept proposals" )
        ;
        
        $menu->addChild( '.icon-monitoring Work Monitoring', array(
            'route' => 'vendor_relation_progress',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "sub_label", "All the work in progress" )
        ;

        $menu->addChild( '.icon-bill Open Bill', array(
            'route' => 'vendor_relation_bill',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "sub_label", "Work completed, but not yet billed" )
        ;

        $menu->addChild( '.icon-invoice Invoices', array(
            'route' => 'vendor_relation_invoices',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "sub_label", "Work completed and invoiced" )
        ;
        
        /*foreach ($menu as $key => $item) {
            $item->setExtra('routes', array(
                'routes' => $key
            ));
        }*/
        
        return $menu;
    }

    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function ordersMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        $routeParameters = array('idOrder' => $idOrder, 'idRelation' => $idRelation);

        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_orders',
            'routeParameters' => array('idRelation' => $idRelation)
        ));
        $menu->setLabel(".icon-orders-dark Proposals");
        
        $menu->addChild( 'Proposal', array(
            'route' => 'vendor_order_show',
            'routeParameters' => $routeParameters
        ));

        $menu->addChild( 'Discussion', array(
            'route' => 'vendor_order_discuss',
            'routeParameters' => $routeParameters
        ));
        
        

        return $menu;
    }

    
    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function progressMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        //$idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        //$routeParameters = array('id' => $idOrder, 'idRelation' => $idRelation);

        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_progress',
            'routeParameters' => array('idRelation' => $idRelation)
        ));
        $menu->setLabel(".icon-orders-dark Work in progress");
              
        return $menu;
    }
    
    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function billMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        //$idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        //$routeParameters = array('id' => $idOrder, 'idRelation' => $idRelation);

        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_bill',
            'routeParameters' => array('idRelation' => $idRelation)
        ));
        $menu->setLabel(".icon-orders-dark Open Bill");
              
        return $menu;
    }
    
    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function invoicesMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $idBill = isset( $options['bill'] ) ? $options['bill']->getId() : -1;
        $routeParameters = array('idBill' => $idBill, 'idRelation' => $idRelation);

        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_invoices',
            'routeParameters' => array('idRelation' => $idRelation)
        ));
        $menu->setLabel(".icon-orders-dark Invoices");
              
        $menu->addChild( 'Invoice', array(
            'route' => 'vendor_invoice_show',
            'routeParameters' => $routeParameters
        ));
        
        return $menu;
    }
}

?>
