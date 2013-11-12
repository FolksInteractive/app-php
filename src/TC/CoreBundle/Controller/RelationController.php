<?php

namespace TC\CoreBundle\Controller;

use Symfony\Component\Form\Form;
use TC\CoreBundle\Entity\Relation;

class RelationController extends Controller {
    
    /**
     * Creates a form to archive a relation by id.
     *
     * @param mixed $id The relation id
     *
     * @return Form The form
     */
    protected function createArchiveForm( $idRelation, $actionRouteName ) {
        return $this->createFormBuilder()
                ->add( "active" )
                        ->setAction( $this->generateUrl( $actionRouteName, array('id' => $idRelation) ) )
                        ->setMethod( 'POST' )
                        ->add( 'submit', 'submit', array('label' => 'Delete') )
                        ->getForm()
        ;
    }

}
