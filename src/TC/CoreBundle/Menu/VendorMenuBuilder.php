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

        $menu = $factory->createItem( 'root' );
        $menu->setChildrenAttribute( "class", "tc-main-menu tc-menu" );

        $menu
            ->addChild( 'Dashboard', array(
                'route' => 'vendor_dashboard'
        ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-dashboard" )
            ->setExtra( "sub_label", "Bird eye view on what's going on" );

        $menu
            ->addChild( 'Clients', array(
            'route' => 'vendor_relation'
        ) )
            ->setExtra( "icon_classes", "icon-relations" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-relations-dark-lg" )
            ->setExtra( "sub_label", "Build & manage relationships" )
                
            // New Relation
            ->addChild('New Relation', array(
            'route' => 'vendor_relation_new'
            ));
        
        $menu
            ->addChild( 'Price Book', array(
            'route' => 'vendor_pricebook'
        ) )
            ->setExtra( "icon_classes", "glyphicon glyphicon-book" )
            ->setExtra( "sub_label", "Your pricing catalog" );
        
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

        $menu = $factory
                    ->createItem( 'root' )
                    ->setChildrenAttribute( "class", "tc-relation-menu tc-menu" );

        // Request For Proposal
         $menu
            ->addChild( 'Request For Proposal', array(
                'route' => 'vendor_relation_rfps',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-rfp-light" )
            ->setExtra( "sub_label", "Describe & discuss your needs" );

         // Proposals
        $menu
            ->addChild( 'Proposals', array(
                'route' => 'vendor_relation_orders',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-orders" )
            ->setExtra( "sub_label", "View, discuss & accept proposals" );
        
        // Monitoring
        $menu
            ->addChild( 'Work Monitoring', array(
                'route' => 'vendor_relation_progress',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-progress-light-sm" )
            ->setExtra( "sub_label", "All the work in progress" );
        
        // Open Bill
        $menu
            ->addChild( 'Open Bill', array(
                'route' => 'vendor_relation_invoice',
                'routeParameters' => $routeParameters
            ) )
            ->setExtra( "icon_classes", "icon-bill" )
            ->setExtra( "sub_label", "Work completed, but not yet invoiced" );
        
        // Invoices 
        $menu
            ->addChild( 'Invoices', array(
                'route' => 'vendor_relation_invoices',
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
        $menu = $factory
            ->createItem( 'root', array(
                'route' => 'vendor_relation_rfps',
                'routeParameters' => array('idRelation' => $idRelation)
            ) )
            ->setLabel( "RFPs" )
            ->setExtra( "breadcrumbs_icon_classes", "icon-rfps-dark" );
            
        // RFP
        if( isset($options['rfp']) && $options['rfp'] instanceof \TC\CoreBundle\Entity\RFP){
            
            $menu
                ->addChild( 'RFP', array(
                    'route' => 'vendor_rfp_show',
                    'routeParameters' => $routeParameters
                ) )
                ->setLabel( $options['rfp']->getHeading() );
        }

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
            'route' => 'vendor_relation_orders',
            'routeParameters' => array('idRelation' => $idRelation)
        ))
            ->setLabel("Proposals")
            ->setExtra( "breadcrumbs_icon_classes", "icon-orders-dark" );
        
        // Proposal
        if( isset($options['order']) && $options['order'] instanceof \TC\CoreBundle\Entity\Order){
            $menu
                ->addChild( 'Proposal', array(
                    'route' => 'vendor_order_show',
                    'routeParameters' => $routeParameters
                ))
                ->setLabel( $options['order']->getHeading() );
        }
                
        // New Proposal
        $menu
            ->addChild( 'New Proposal', array(
                'route' => 'vendor_order_new',
                'routeParameters' => array('idRelation' => $idRelation)
            ) );
        
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

        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_progress',
            'routeParameters' => array('idRelation' => $idRelation)
        ))
            ->setLabel("Work Monitoring")
            ->setExtra( "breadcrumbs_icon_classes", "icon-progress-dark-lg" );
              
        return $menu;
    }
    
    /**
     * Breadcrumb menu for relation invoiceing section
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
            'route' => 'vendor_relation_invoice',
            'routeParameters' => array('idRelation' => $idRelation)
        ))
            ->setLabel("Open Bill")
            ->setExtra( "breadcrumbs_icon_classes", "icon-bill-dark-lg" );
              
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
        $idInvoice = isset( $options['invoice'] ) ? $options['invoice']->getId() : -1;
        $routeParameters = array('idInvoice' => $idInvoice, 'idRelation' => $idRelation);

        // Invoices
        $menu = $factory->createItem( 'root', array(
            'route' => 'vendor_relation_invoices',
            'routeParameters' => array('idRelation' => $idRelation)
        ))
            ->setLabel("Invoices")
            ->setExtra( "breadcrumbs_icon_classes", "icon-invoices-dark-lg" );
        
        // Invoice      
        $menu->addChild( 'Invoice', array(
            'route' => 'vendor_invoice_show',
            'routeParameters' => $routeParameters
        ));
        
        return $menu;
    }
}

?>
