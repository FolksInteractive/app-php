<?php

namespace TC\CoreBundle\Controller;

use Symfony\Component\Form\Form;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\OrderType;

/**
 * Abstract Order controller.
 */
class OrderController extends Controller {

    
    /**
     * Creates a form to create a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    protected function createOrderForm( $order, $idRelation ) {
        $form = $this->createForm( new OrderType(), $order, array(
            'method' => 'POST',
                ) );
        
        // We remove the entity field since we know to which relation 
        // the new order belongs and also because it is complicated 
        // to just store it in a hidden field.
        $form->remove("relation"); 
        
        $form->add( 'submit', 'submit', array('label' => 'Create') );

        return $form;
    }
}
