<?php

namespace TC\FeedbackBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/feedback")
 *
 */
class FeedbackController extends Controller {
    
    public function newAction( $uri  ) {
        $form = $this->createFeebackForm();
        $form->setData( array("uri" => $uri ) );
        
        return $this->render( 'TCFeedbackBundle::new.html.twig', array("form" => $form->createView()) );
    }

    /**
     * @Route("/", name="feedback_post")
     * @Method({"POST"})
     */
    public function postAction( Request $request ) {
        $form = $this->createFeebackForm();
        $form->handleRequest( $request );

        if ( $form->isValid() ) {

            $this->sendEmailMessage( $form->getData() );
        }
        
        return new Response();
    }

    private function createFeebackForm( ) {
        return $this->createFormBuilder(null, array(
            "action" => $this->generateUrl( "feedback_post")
        ))
                ->add( "body", "textarea" )
                ->add( "uri", "hidden")
                ->add( "submit", "submit")
                ->getForm();
    }

    private function sendEmailMessage( $data ) {            
        $renderedTemplate = $this->renderView( 'TCFeedbackBundle::email.txt.twig', array('data' => $data) );

        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode( "\n", trim( $renderedTemplate ) );
        $subject = $renderedLines[0];
        $body = implode( "\n", array_slice( $renderedLines, 1 ) );

        $from = $this->container->getParameter( 'tc_feedback.form_email' );
        $message = Swift_Message::newInstance()
                ->setSubject( $subject )
                ->setFrom( $from )
                ->setTo( array("fpoirier@timecrumbs.com", /*"alexlm@timecrumbs.com", $from*/) )
                ->setBody( $body )
                ->setContentType( "text/html" );

        $this->container->get( 'mailer' )->send( $message );
    }

}
