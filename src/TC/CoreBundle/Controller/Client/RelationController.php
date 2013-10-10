<?php

namespace TC\CoreBundle\Controller\Client;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Controller\RelationController as BaseController;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Form\RelationCreateType;

/**
 * Relation controller.
 *
 * @Route("/r")
 */
class RelationController extends BaseController {

    /**
     * Listing of Relations.
     *
     * @Route("/", name="client_relation")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Relation/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $relations  = $this->getRelationManager()->getClientRelations();        

        return array(
            'relations' => $relations
        );
    }
    
    /**
     * Creates a new relation.
     *
     * @Route("/", name="client_relation_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Client:Relation/new.html.twig")
     */
    public function createAction( Request $request ) {
        $relation = $this->getRelationManager()->createClientRelation();
        $form = $this->createCreateForm( $relation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getRelationManager()->saveRelation($relation);

            return $this->redirect( $this->generateUrl( 'client_relation_orders', array('idRelation' => $relation->getId()) ) );
        }

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new relation.
     *
     * @Route("/new", name="client_relation_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Client:Relation/new.html.twig")
     */
    public function newAction() {
        $relation = $this->getRelationManager()->createClientRelation();
        $form = $this->createCreateForm( $relation );

        return array(
            'relation' => $relation,
            'form' => $form->createView(),
        );
    }

    /**
     * Archive a relation.
     *
     * @Route("/{idRelation}", name="client_relation_archive")
     * @Method("POST")
     */
    public function archiveAction( Request $request, $idRelation ) {
        $relation = $this->getRelationManager()->findClientRelation($idRelation);
        
        $form = $this->createArchiveForm( $idRelation );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $this->getRelationManager()->archiveRelation($relation);
            $this->getRelationManager()->saveRelation($relation);
        }

        return $this->redirect( $this->generateUrl( 'dashboard' ) );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * Creates a form to create a Relation.
     *
     * @param Relation $relaiton The relaiton
     *
     * @return Form The form
     */
    protected function createCreateForm( Relation $relation ){
        $form = $this->createForm( new RelationCreateType(), $relation, array(
            'action' => $this->generateUrl( 'client_relation_create', array('idRelation' => $relation->getId()) ),
            'method' => 'POST',
                ) );
        
        $form->remove('clientEnrollment');
        
        $form->add( 'submit', 'submit', array('label' => 'Create') );

        return $form;
    }
}