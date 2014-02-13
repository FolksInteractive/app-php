<?php
namespace TC\CoreBundle\Controller;

use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;

class ApiController extends Controller {
    
    public function getWorkspaceAction( ){
                
        
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $this->getWorkspace() );
        $view->setSerializationContext( $this->getContext( array("details") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }
    
    public function getVendorsAction( ){
                
        $vendors = $this->getRelationManager()->findAllVendors( );
        
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $vendors );
        $view->setSerializationContext( $this->getContext( array("list") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getClientsAction( ){
                
        $vendors = $this->getRelationManager()->findAllClients( );
            
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $vendors );
        $view->setSerializationContext( $this->getContext( array("list") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }    
    /**
     * 
     * @param array $groups
     * @return SerializationContext
     */
    private function getContext( array $groups ) {
        $context = new SerializationContext();
        $context->enableMaxDepthChecks();
        $context->setVersion( "0" );
        $context->setGroups( $groups );

        return $context;
    }
}

?>
