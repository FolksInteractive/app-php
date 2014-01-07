<?php

namespace TC\CoreBundle\Mailer;

use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Routing\RouterInterface;
use TC\CoreBundle\Entity\Order;
use TC\CoreBundle\Entity\Relation;
use TC\CoreBundle\Entity\RFP;

class Mailer {

    /** @var Swift_Mailer */
    protected $mailer;
    /** @var RouterInterface */
    protected $router;
    /** @var Logger */
    protected $logger;
    /** @var EngineInterface */
    protected $templating;
    /** @var string */
    protected $from_email;

    /**
     * 
     * @param Swift_Mailer $mailer
     * @param RouterInterface $router
     * @param Logger $logger
     * @param EngineInterface $templating
     * @param string $from_email
     */
    public function __construct(Swift_Mailer $mailer, RouterInterface $router, Logger $logger, EngineInterface $templating, $from_email) {
        $this->mailer = $mailer;
        $this->router = $router;
        $this->logger = $logger;
        $this->templating = $templating;
        $this->from_email = $from_email;
    }

    /**
     * @param Relation $relation
     */
    public function sendClientInvitation(Relation $relation) {
        $email = $relation->getClient()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:relation_invitation_client_email.txt.twig', array('relation' => $relation));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Relation #%s invitation client sent to %s", $relation->getId(), $email) );
    }
    
    /**
     * 
     * @param Relation $relation
     */
    public function sendVendorInvitation(Relation $relation) {
        $email = $relation->getVendor()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:relation_invitation_vendor_email.txt.twig', array('relation' =>  $relation));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Relation #%s invitation  vendor sent to %s", $relation->getId(), $email) );
    }

    /**
     * 
     * @param Order $order
     */
    public function sendOrderPurchaseNotification(Order $order) {
        $email = $order->getRelation()->getVendor()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:order_purchase_email.txt.twig', array('relation' =>  $order->getRelation(), 'order' =>  $order));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Order #%s purchase notification sent to %s", $order->getId(), $email) );        
    }

    /**
     * 
     * @param Order $order
     */
    public function sendOrderReadyNotification(Order $order) {
        $email = $order->getRelation()->getClient()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:order_ready_email.txt.twig', array('relation' =>  $order->getRelation(), 'order' =>  $order));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Order #%s ready notification sent to %s", $order->getId(), $email) );        
    }
    

    /**
     * 
     * @param RFP $rfp
     */
    public function sendRFPReadyNotification(RFP $rfp) {
        $relation = $rfp->getRelation();
        
        $email = $relation->getVendor()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:rfp_ready_email.txt.twig', array('relation' =>  $relation, 'rfp' =>  $rfp));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("RFP #%s ready notification sent to %s", $rfp->getId(), $email) );        
    }
        
    /**
     * 
     * @param RFP $rfp
     * @param object $cancellation See Client/RFPController::createCancelForm
     */
    public function sendRFPCancellation(RFP $rfp, $cancellation = null) {
        $relation = $rfp->getRelation();
        
        $email = $relation->getVendor()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:rfp_cancel_email.txt.twig', array('relation' =>  $relation,'cancellation'=>$cancellation));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("RFP #%s cancellation sent to %s", $rfp->getId(), $email) );
    }
        
    /**
     * 
     * @param RFP $rfp
     * @param object $refusal See Vendor/RFPController::createDeclineForm
     */
    public function sendRFPDeclinal(RFP $rfp, $refusal = null) {
        $relation = $rfp->getRelation();
        
        $email = $relation->getClient()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:rfp_decline_email.txt.twig', array('relation' =>  $relation,'refusal'=>$refusal));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("RFP #%s declinal sent to %s", $rfp->getId(), $email) );
    }
        
    /**
     * 
     * @param Order $order
     * @param object $cancellation See Client/RFPController::createCancelForm
     */
    public function sendOrderCancellation(Order $order, $cancellation = null) {
        $relation = $order->getRelation();
        
        $email = $relation->getClient()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:order_cancel_email.txt.twig', array('relation' =>  $relation,'cancellation'=>$cancellation));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Order #%s cancellation sent to %s", $order->getId(), $email) );
    }
        
    /**
     * 
     * @param Order $order
     * @param object $refusal See Vendor/RFPController::createDeclineForm
     */
    public function sendOrderRefusal(Order $order, $refusal = null) {
        $relation = $order->getRelation();
        
        $email = $relation->getVendor()->getEmail();
        
        $rendered = $this->templating->render('TCCoreBundle:Notification:order_decline_email.txt.twig', array('relation' =>  $relation,'refusal'=>$refusal));
        $this->sendEmailMessage($rendered, $email);
        
        $this->logger->addInfo( sprintf("Order #%s refusal sent to %s", $order->getId(), $email) );
    }
    
    /**
     * 
     * @param string $renderedTemplate
     * @param string $toEmail
     * @param string $file_path
     */
    protected function sendEmailMessage($renderedTemplate, $toEmail, $file_path = null) {
        // Render the email, use the first line as the subject, and the rest as the body
        $renderedLines = explode("\n", trim($renderedTemplate));
        $subject = $renderedLines[0];
        $body = implode("\n", array_slice($renderedLines, 1));

        $message = Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($this->from_email, "TimeCrumbs")
                ->setTo($toEmail)
                ->setBody($body)
                ->setContentType("text/html");
        
        if (file_exists($file_path) && !is_dir($file_path))
            $message->attach(Swift_Attachment::fromPath($file_path));
        
        $this->mailer->send($message);
    }
}
