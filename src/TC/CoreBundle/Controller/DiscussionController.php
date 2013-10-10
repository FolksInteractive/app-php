<?php

namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TC\CoreBundle\Entity\Comment;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Form\CommentType;

class DiscussionController extends Controller {

    /**
     * Finds and displays a Order discussion.
     */
    protected function discussAction( $idRelation, $id ) {
        $relation = $this->getRelationManager()->findRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        $comment = $this->getOrderManager()->createComment( $order );

        $form = $this->createCommentForm( $comment, $order );
        return array(
            'order' => $order,
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * Use javascript sync
     */
    protected function syncDiscussAction( Request $request, $idRelation, $id ) {
        $relation = $this->getRelationManager()->findRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        $responseData = array("thread" => array());

        if ( $request->query->has( "pull" ) ) {
            $responseData["thread"] = $order->getThread();
        } else if ( $order->getThread()->getNumComments() > 0 ) {
            $lastCommentId = $order->getThread()->getComments()->last()->getId();
            $responseData["lastCommentId"] = $lastCommentId;
        } else {
            $responseData["lastCommentId"] = -1;
        }

        return new Response( json_encode( $responseData ) );
    }

    /**
     * Handle new comment submit
     */
    protected function commentAction( Request $request, $idRelation, $id ) {
        $relation = $this->getRelationManager()->findRelation( $idRelation );
        $order = $this->getOrderManager()->findOrder( $relation, $id );

        $comment = $this->getOrderManager()->createComment( $order );

        $form = $this->createCommentForm( $comment, $order );
        $form->handleRequest( $request );

        if ( $form->isValid() ) {
            $order->getThread()->addComment( $comment );
            $this->getOrderManager()->saveOrder($order);

            if ( $request->isXmlHttpRequest() ) {
                $response = new Response( json_encode( $comment ) );
                $response->setStatusCode( 200 );
                return $response;
            }
        }

        return array(
            'order' => $order,
            'relation' => $relation,
            'form' => $form->createView()
        );
    }

    /**
     * Creates a form to edit a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    protected function createCommentForm( Comment $comment, Order $order ) {
        $form = $this->createForm( new CommentType(), $comment, array(
            'method' => 'POST',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Say it') );
        return $form;
    }

}
