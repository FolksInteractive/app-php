<?php

namespace TC\CoreBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerAware;

class ClientMenuBuilder extends ContainerAware {

    /**
     * Build the main menu
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function mainMenu( FactoryInterface $factory, array $options ) {

        $menu = $factory->createItem( 'root'/* , array('childrenAttributes' => array('id' => 'menu_id')) */ );
        $menu->setChildrenAttribute( "class", "tc-main-menu tc-menu" );
        //$menu->setUri($this->container->get('request')->getRequestUri());
        
        // Dashboard
        $menu
            ->addChild( 'Dashboard', array(
                'route' => 'client_dashboard'
            ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-dashboard" )
            ->setExtra( "sub_label", "Bird eye view on what's going on" );

        // Service Providers
        $menu
            ->addChild( 'Service Providers', array(
            'route' => 'client_relation'
        ) )
            ->setExtra( "icon_classes", "icon-relations" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-relations-dark-large" )
            ->setExtra( "sub_label", "Build & manage relationships" )
        
            // New Relation
            ->addChild('New Relation', array(
            'route' => 'client_relation_new'
            ));
        
        // Projects
        $menu
            ->addChild( 'Projects', array(
                'route' => 'client_project'
            ) )
            ->setExtra( "icon_classes", "icon-projects" )
            ->setExtra( "sub_label", "Manage multiple service providers in the same project" );

        return $menu;
    }

    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function projectMenu( FactoryInterface $factory, array $options ) {
        $idProject = $options['project']->getId();

        $routeParameters = array('idProject' => $idProject);

        $menu = $factory->createItem( 'root'/* , array('childrenAttributes' => array('id' => 'menu_id')) */ );
        
        $menu
            ->setChildrenAttribute( "class", "tc-project-menu tc-menu" )
            ->setLabel( "Projects" )
            ->setExtra( "icon_classes", "icon-projects-dark" );
        //$menu->setUri($this->container->get('request')->getRequestUri());

        /* $menu->addChild( '.icon-settings Settings', array(
          'route' => 'client_relation_new',
          'routeParameters' => $routeParameters
          ) );
         */

        /* foreach ($menu as $key => $item) {
          $item->setExtra('routes', array(
          'routes' => $key
          ));
          } */

        return $menu;
    }

    /**
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function relationMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $user = $options['user'];

        $routeParameters = array('idRelation' => $idRelation);

        $menu = $factory
                    ->createItem( 'root' )
                    ->setChildrenAttribute( "class", "tc-relation-menu tc-menu" );
        
        // Request For Proposal
        $menu
            ->addChild( 'Request For Proposal', array(
            'route' => 'client_relation_rfps',
            'routeParameters' => $routeParameters
        ) )
            ->setExtra( "icon_classes", "icon-rfp-light" )
            ->setExtra( "sub_label", "Describe & discuss your needs" );
        
        // Proposals
        $menu
            ->addChild( 'Proposals', array(
                'route' => 'client_relation_orders',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-orders" )
            ->setExtra( "sub_label", "View, discuss & accept proposals" );
        
        // Monitoring
        $menu
            ->addChild( 'Work Monitoring', array(
                'route' => 'client_relation_progress',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-progress-lt-sm" )
            ->setExtra( "sub_label", "All the work in progress" );
        
        // Open Bill
        $menu
            ->addChild( 'Open Bill', array(
                'route' => 'client_relation_bill',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-bill" )
            ->setExtra( "sub_label", "Work completed, but not yet billed" );
        
        // Invoices
        $menu
            ->addChild( 'Invoices', array(
                'route' => 'client_relation_invoices',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-invoice" )
            ->setExtra( "sub_label", "Work completed and invoiced" );

        return $menu;
    }

    /**
     * Breadcrumb menu for relation rfps section
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function rfpsMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $idRFP = isset( $options['rfp'] ) ? $options['rfp']->getId() : -1;
        $routeParameters = array('idRFP' => $idRFP, 'idRelation' => $idRelation);
        
        // RFPs
        $menu = $factory->createItem( 'root', array(
            'route' => 'client_relation_rfps',
            'routeParameters' => array('idRelation' => $idRelation)
        ) )
            ->setLabel( "RFPs" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-rfps-dark" );
        
        // RFP
        if( isset($options['rfp']) && $options['rfp'] instanceof \TC\CoreBundle\Entity\RFP){
            
            $menu->addChild( 'RFP', array(
                'route' => 'client_rfp_show',
                'routeParameters' => $routeParameters
            ) )
            ->setLabel( $options['rfp']->getHeading() );
        }
        
        // New RFP
        $menu->addChild( 'New RFP', array(
            'route' => 'client_rfp_new',
            'routeParameters' => array('idRelation' => $idRelation)
        ) );
        
        

        return $menu;
    }

    /**
     * Breadcrumb menu for relation propsal section
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function ordersMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        $routeParameters = array('idOrder' => $idOrder, 'idRelation' => $idRelation);
        
        // Proposals
        $menu = $factory->createItem( 'root', array(
            'route' => 'client_relation_orders',
            'routeParameters' => array('idRelation' => $idRelation)
        ) )
            ->setLabel( "Proposals" )                
            ->setExtra( "breadcrumbs_icon_classes", "icon-orders-dark" );
        
        // Proposal        
        if( isset($options['order']) && $options['order'] instanceof \TC\CoreBundle\Entity\Order){
            $menu->addChild( 'Proposal', array(
                'route' => 'client_order_show',
                'routeParameters' => $routeParameters
            ) )
            ->setLabel( $options['order']->getHeading() );
        }
        
        return $menu;
    }

    /**
     * Breadcrumb menu for relation monitoring section
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function progressMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        //$idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        //$routeParameters = array('id' => $idOrder, 'idRelation' => $idRelation);
        
        // Work Monitoring
        $menu = $factory->createItem( 'root', array(
            'route' => 'client_relation_progress',
            'routeParameters' => array('idRelation' => $idRelation)
        ) )
            ->setLabel( "Work Monitoring" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-progress-dk-lg" );
        
        return $menu;
    }

    /**
     * Breadcrumb menu for relation billing section
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function billMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        //$idOrder = isset( $options['order'] ) ? $options['order']->getId() : -1;
        //$routeParameters = array('id' => $idOrder, 'idRelation' => $idRelation);
        
        // Open Bill
        $menu = $factory->createItem( 'root', array(
            'route' => 'client_relation_bill',
            'routeParameters' => array('idRelation' => $idRelation)
        ) )
            ->setLabel( "Open Bill" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-orders-dark" );

        return $menu;
    }

    /**
     * Breadcrumb menu for relation invoicing section
     * 
     * @param FactoryInterface $factory
     * @param array $options
     * @return MenuItem
     */
    public function invoicesMenu( FactoryInterface $factory, array $options ) {
        $idRelation = $options['relation']->getId();
        $idBill = isset( $options['bill'] ) ? $options['bill']->getId() : -1;
        $routeParameters = array('idBill' => $idBill, 'idRelation' => $idRelation);
        
        // Invoices
        $menu = $factory->createItem( 'root', array(
            'route' => 'client_relation_invoices',
            'routeParameters' => array('idRelation' => $idRelation)
        ) )
            ->setLabel( "Invoices" )
            ->setExtra( "icon_classes", "icon-orders-dark" );
        
        // Invoice
        $menu->addChild( 'Invoice', array(
            'route' => 'client_invoice_show',
            'routeParameters' => $routeParameters
        ) );

        return $menu;
    }

}

?>
