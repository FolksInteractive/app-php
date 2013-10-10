<?php

namespace TC\CoreBundle\Mailer;

use Swift_Attachment;
use Swift_Message;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use TC\CoreBundle\Entity\Relation;

class Mailer {

    protected $mailer;
    protected $router;
    protected $logger;
    protected $templating;
    protected $from_email;

    public function __construct($mailer, RouterInterface $router, Logger $logger, EngineInterface $templating, $from_email) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->logger = $logger;
        $this->templating = $templating;
        $this->from_email = $from_email;
    }

    public function sendClientInvitation(Relation $relation) {
        $enrollment = $relation->getClientEnrollment();
        $rendered = $this->templating->render('TCCoreBundle:Client:Relation/invitation_email.txt.twig', array('relation' => $relation, 'enrollment' => $enrollment));
        $this->sendEmailMessage($rendered, $enrollment->getEmail());
        
        $this->logger->addDebug( sprintf("Invitation sent to %s", $enrollment->getEmail()) );
    }
    
    public function sendVendorInvitation(Relation $relation) {
        $enrollment = $relation->getVendorEnrollment();
        $rendered = $this->templating->render('TCCoreBundle:Vendor:Relation/invitation_email.txt.twig', array('relation' =>  $relation, 'enrollment' => $enrollment));
        $this->sendEmailMessage($rendered, $enrollment->getEmail());
        
        $this->logger->addDebug( sprintf("Invitation sent to %s", $enrollment->getEmail()) );
    }

    protected function sendEmailMessage($renderedTemplate, $toEmail, $file_path = null) {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from_email)
                ->setTo($toEmail)
                ->setBody($body)
                ->setContentType("text/html");
        
        if (file_exists($file_path) && !is_dir($file_path))
            $message->attach(Swift_Attachment::fromPath($file_path));
        
        $this->mailer->send($message);
    }

}
