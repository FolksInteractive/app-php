<?php

namespace TC\CoreBundle\Twig;

use Knp\Menu\MenuItem;
use Knp\Menu\Util\MenuManipulator;
use Knp\Menu\Silex\Voter\RouteVoter;
use Twig_Extension;
use Twig_SimpleFilter;

class BreadcrumbsExtension extends Twig_Extension
{
    /**
     *
     * @var RouteVoter 
     */
    private $voter;
    
    public function __construct(RouteVoter $voter){
        $this->voter = $voter;
    }
    
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('breadcrumbs', array($this, 'breadcrumbsFilter')),
        );
    }

    public function breadcrumbsFilter(MenuItem $menu)
    {
        $menu = $this->getCurrentMenuItem( $menu );
        
        $manipulator = new MenuManipulator();
        $breadcrumbMenu = $manipulator->getBreadcrumbsArray($menu);
        
        // If first item is 'root' we don't wan't it in the breadcrumb
        if( $breadcrumbMenu[0]['label'] == 'root'){
            array_shift($breadcrumbMenu);
        }
        
        return $breadcrumbMenu;
    }
    
    /**
     * 
     * @param MenuItem $menu
     * @return MenuItem
     */
    private function getCurrentMenuItem(MenuItem $menu ) {
        
        foreach ( $menu as $item ) {
            if ( $this->voter->matchItem( $item ) ) {
                return $item;
            }

            if ( $item->getChildren() && $currentChild = $this->getCurrentMenuItem( $item ) ) {
                return $currentChild;
            }
        }

        return $menu;
    }

    public function getName()
    {
        return 'tc_menu';
    }
}
?>
