<?php

namespace TC\ApiBundle\Controller;

use TC\ApiBundle\Entity\Workspace;
use TC\ApiBundle\Model\EnrollmentManager;
use TC\ApiBundle\Model\RelationManager;
use TC\ApiBundle\Model\WorkspaceManager;
use TC\ApiBundle\TransfertObject\RelationTO;
use TC\UserBundle\Model\UserManager;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\View\View;
use InvalidArgumentException;
use JMS\Serializer\SerializationContext;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\Validator;

/**
 * 
 * @RouteResource("Relation")
 */
class RelationsController extends Controller {

    public function cgetAction() {
        $relations = $this->getRelationManager()->getRelations();

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $relations );
        $view->setSerializationContext( $this->getContext( array("relation") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function getAction( $id ) {

        $relation = $this->getRelationManager()->findRelation( $id );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $relation );
        $view->setSerializationContext( $this->getContext( array("relation") ) );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function postAction() {

        $relationTO = new RelationTO( $this->getRequest()->request->get( "relation" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $relationTO, array("create") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $relation = $this->getRelationManager()->createRelation();

        if ( $relationTO->name )
            $relation->setName( $relationTO->name );

        if ( $relationTO->description )
            $relation->setDescription( $relationTO->description );

        // Assign vendor to relation or send enrollment if user not registered yet
        if ( $relationTO->vendor ) {
            $this->getRelationManager()->assignVendor( $relation, $relationTO->vendor );
        }

        // Assign client to relation or send enrollment if user not registered yet
        if ( $relationTO->client ) {
            $this->getRelationManager()->assignClient( $relation, $relationTO->client );
        }

        // Assign collaborators to relation or send enrollment if user not registered yet
        if ( $relationTO->collaborators ) {
            foreach ( $relationTO->collaborators as $key => $value ) {
                $this->getRelationManager()->assignCollaborator( $relation, $value );
            }
        }

        $this->getRelationManager()->saveRelation( $relation );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 201 );
        $view->setData( $relation );
        $view->setSerializationContext( $this->getContext( array("relation") ) );

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    /**
     * Edit Relation
     */
    public function putAction( $id ) {
        $relationTO = new RelationTO( $this->getRequest()->request->get( "relation" ) );

        // Validates the request before business logic
        $errors = $this->getValidator()->validate( $relationTO, array("edit") );
        if ( $errors->count() > 0 )
            throw new InvalidArgumentException( $errors->get( 0 )->getMessage() );

        // BUSINESS LOGIC  
        $relation = $this->getRelationManager()->findRelation( $id );

        if ( $relationTO->name )
            $relation->setName( $relationTO->name );

        if ( $relationTO->description )
            $relation->setDescription( $relationTO->description );

        // Assign vendor to relation or send enrollment if user not registered yet
        if ( $relationTO->vendor ) {
            $this->getRelationManager()->assignVendor( $relation, $relationTO->vendor  );
        }

        // Assign client to relation or send enrollment if user not registered yet
        if ( $relationTO->client ) {
            $this->getRelationManager()->assignClient( $relation, $relationTO->client );
        }

        // Assign collaborators to relation or send enrollment if user not registered yet
        if ( $relationTO->collaborators ) {
            foreach ( $relationTO->collaborators as $key => $value ) {
                $this->getRelationManager()->assignCollaborator( $relation, $value );
            }
        }
        
        $this->getRelationManager()->saveRelation( $relation );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 200 );
        $view->setData( $relation );
        $view->setSerializationContext( $this->getContext( array("relation") ) );

        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    public function removeAction( $id ) {
        $relation = $this->getRelationManager()->findRelation( $id );

        $this->getRelationManager()->removeRelation( $relation );

        /* @var $view View */
        $view = View::create();
        $view->setStatusCode( 204 );
        $view->setData( array() );
        return $this->get( 'fos_rest.view_handler' )->handle( $view );
    }

    /**
     * @return UserManager
     */
    private function getUserManager() {
        return $this->container->get( "tc_user.manager.user" );
    }

    /**
     * @return RelationManager
     */
    private function getRelationManager() {
        return $this->container->get( "tc.manager.relation" );
    }

    /**
     * @return WorkspaceManager
     */
    private function getWorkspaceManager() {
        return $this->container->get( "tc.manager.workspace" );
    }

    /**
     * @return EnrollmentManager
     */
    private function getEnrollmentManager() {
        return $this->container->get( "tc.manager.enrollment" );
    }

    /**
     * @return Validator
     */
    private function getValidator() {
        return $this->get( 'validator' );
    }

    /**
     * 
     * @param array $groups
     * @return SerializationContext
     */
    private function getContext( array $groups ) {
        $context = new SerializationContext();
        $context->setVersion( "0" );
        $context->setGroups( $groups );

        return $context;
    }

    /**
     * Returns the current user's workspace
     * 
     * @return Workspace
     */
    private function getUserWorkspace() {
        return $this->container->get( 'security.context' )->getToken()->getUser()->getWorkspace();
    }

}

?>
