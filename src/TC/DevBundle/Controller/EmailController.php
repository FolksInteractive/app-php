<?php

namespace TC\DevBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmailController extends Controller
{
    /**
     * @Route("/email/{name}")
     */
    public function emailAction($name)
    {
        $view = "";
        $params = array();
        
        switch($name){
            
            // Confirmation/activation message sent when a user creates a new account
            case 1 :
            case "registration" :
                $view = "TCUserBundle:Registration:email.txt.twig";
                $params["user"] = $this->getUser();
                $params["confirmationUrl"] = "{{(confirmationUrl)}}";
                break;
            
            // Resetting password
            case 2 :
            case "resetting" :
                $view = "TCUserBundle:Resetting:email.txt.twig";
                $params["user"] = $this->getUser();
                $params["confirmationUrl"] = "{{(confirmationUrl)}}";
                break;
            
            // I'm a vendor, I invite a client to my contact list
            case 3 :
            case "client_invitation" :
                $view = "TCCoreBundle:Notification:relation_invitation_client_email.txt.twig";
                $params["relation"] = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                break;
            
            // I'm a client, I invite a vendor to my contact list
            case 4 :
            case "vendor_invitation" :
                $view = "TCCoreBundle:Notification:relation_invitation_vendor_email.txt.twig";
                $params["relation"] = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                break;
            
            // Client create and sent a new RFP to his vendor
            case 5 :
            case "rfp_ready" :
                $view = "TCCoreBundle:Notification:rfp_ready_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                $params["relation"] = $relation;
                $params["rfp"] = $relation->getRFPs()->get(0);
                break;
            
            // Client decide to finally cancel the RFP
            case 6 :
            case "rfp_cancel" :
                $view = "TCCoreBundle:Notification:rfp_cancel_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                $params["relation"] = $relation;
                $params["rfp"] = $relation->getRFPs()->get(0);
                break;
            
            // Vendor decide to decline an RFP
            case 7 :
            case "rfp_decline" :
                $view = "TCCoreBundle:Notification:rfp_decline_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                $params["relation"] = $relation;
                $params["rfp"] = $relation->getRFPs()->get(0);
                break;
            
            // Vendor create and send a new proposal to his client
            case 8 :
            case "order_ready" :
                $view = "TCCoreBundle:Notification:order_ready_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                $params["relation"] = $relation;
                $params["order"] = $relation->getOrders()->get(0);
                break;
            
            // Vendor decide to cancel the proposal
            case 9 :
            case "order_cancel" :
                $view = "TCCoreBundle:Notification:order_cancel_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                $params["relation"] = $relation;
                $params["order"] = $relation->getOrders()->get(0);
                break;
            
            // Client decide to decline the proposal
            case 10 :
            case "order_decline" :
                $view = "TCCoreBundle:Notification:order_decline_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                $params["relation"] = $relation;
                $params["order"] = $relation->getOrders()->get(0);
                break;
            
            // Client decide to purchase the proposal
            case 11 :
            case "order_purchase" :
                $view = "TCCoreBundle:Notification:order_purchase_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                $params["relation"] = $relation;
                $params["order"] = $relation->getOrders()->get(0);
                break;
            
            // Vendor sends an invoice to his client
            case 12 :
            case "invoice_ready" :
                $view = "TCCoreBundle:Notification:invoice_ready_email.txt.twig";
                $relation = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                $params["relation"] = $relation;
                //$params["invoice"] = $relation->getInvoice()->get(0);
                break;
            
        }
        
        return $this->render($view, $params);
    }
}
