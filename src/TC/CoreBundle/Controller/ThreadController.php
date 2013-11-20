<?php

namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use TC\CoreBundle\Entity\Comment;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Thread;
use TC\CoreBundle\Form\CommentType;

/**
 * Discussion controller.
 *
 * @Route("/thread/{idThread}")
 */
class ThreadController extends Controller {

    /**
     * @Route("/sync", name="thread_sync")
     */
    public function syncDiscussAction( Request $request, $idThread ) {
        $thread = $this->getThreadManager()->findThread( $idThread );

        $responseData = array("thread" => array());

        if ( $request->query->has( "pull" ) ) {
            $responseData["thread"] = $thread;
        } else if ( $thread->getNumComments() > 0 ) {
            $lastTimestamp = $thread->getComments()->last()->getCreatedAt()->format( "Y-m-d H:i:s" );
            $responseData["lastTimestamp"] = $lastTimestamp;
        } else {
            $responseData["lastTimestamp"] = -1;
        }

        return new Response( json_encode( $responseData ) );
    }

    /**
     * @Route("/comment", name="thread_comment")
     * @Method({"POST"})
     */
    public function commentAction( Request $request, $idThread ) {
        $thread = $this->getThreadManager()->findThread( $idThread );

        $data = json_decode($request->getContent(), true);
        
        $comment = $this->getThreadManager()->createComment( $thread );

        $form = $this->createCommentForm( $comment, $thread );
        $form->submit( $data );
        
        $response = new Response( );
        $response->setStatusCode( 400 );
        
        if ( $form->isValid() ) {
            $thread->addComment( $comment );
            $this->getThreadManager()->saveComment($comment);

            $response->setContent( json_encode( $comment ) );
            $response->setStatusCode( 200 );
        }

        
        return $response;
    }

    /**
     * Creates a form to edit a Order.
     *
     * @param Order $order The order
     *
     * @return Form The form
     */
    protected function createCommentForm( Comment $comment, Thread $thread ) {
        $form = $this->createForm( new CommentType(), $comment, array(
            'method' => 'POST',
                ) );

        $form->add( 'submit', 'submit', array('label' => 'Say it') );
        return $form;
    }

}
