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
            case 1 :
            case "registration" :
                $view = "TCUserBundle:Registration:email.txt.twig";
                $params["user"] = $this->getUser();
                $params["confirmationUrl"] = "{{(confirmationUrl)}}";
                break;
            
            case 2 :
            case "resetting" :
                $view = "TCUserBundle:Resetting:email.txt.twig";
                $params["user"] = $this->getUser();
                $params["confirmationUrl"] = "{{(confirmationUrl)}}";
                break;
            
            case 3 :
            case "client_invitation" :
                $view = "TCCoreBundle:Client:Notification/relation_invitation_email.txt.twig";
                $params["relation"] = $this->getUser()->getWorkspace()->getClientRelations()->get(0);
                break;
            
            case 4 :
            case "vendor_invitation" :
                $view = "TCCoreBundle:Vendor:Notification/relation_invitation_email.txt.twig";
                $params["relation"] = $this->getUser()->getWorkspace()->getVendorRelations()->get(0);
                break;
            
            case 5 :
            case "activation" :
                $view = "TCUserBundle:Activation:email.txt.twig";
                $params["user"] = $this->getUser();
                $params["confirmationUrl"] = "{{(confirmationUrl)}}";
                break;
        }
        
        return $this->render($view, $params);
    }
}
