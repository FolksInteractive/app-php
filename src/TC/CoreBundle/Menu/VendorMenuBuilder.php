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

        $menu->addChild( '.icon-dashboard Dashboard', array(
            'route' => 'vendor_dashboard'
        ) );
        
        $menu->addChild( '.icon-relations Relationships', array(
            'route' => 'vendor_relation'
        ) );

        $menu->addChild( '.icon-pricebook Pricebook', array(
            'uri' => '#'
        ) );
        
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

        $menu->addChild( '.icon-agreement Master Agreement', array(
            'route' => 'vendor_relation_new',
            'routeParameters' => $routeParameters
        ) );

        $ordersItem = $menu->addChild( '.icon-orders Work orders', array(
            'route' => 'vendor_relation_orders',
            'routeParameters' => $routeParameters
                ) );

        $menu->addChild( '.icon-work-progress Work in progress', array(
            'route' => 'vendor_relation_progress',
            'routeParameters' => $routeParameters
        ) );

        $menu->addChild( '.icon-bill Open Bill', array(
            'route' => 'vendor_relation_bill',
            'routeParameters' => $routeParameters
        ) );

        $menu->addChild( '.icon-invoice Invoices', array(
            'route' => 'vendor_relation_invoices',
            'routeParameters' => $routeParameters
        ) );

        $menu->addChild( '.icon-settings Settings', array(
            'route' => 'vendor_relation_new',
            'routeParameters' => $routeParameters
        ) );
        
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
        $menu->setLabel(".icon-orders-dark Work Orders");
        
        $menu->addChild( 'Work Order', array(
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
