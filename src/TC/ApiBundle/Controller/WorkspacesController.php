<?php

namespace TC\ApiBundle\Controller;

use TC\UserBundle\Entity\User;
use TC\ApiBundle\Entity\Workspace;
use TC\ApiBundle\Entity\Relation;
use FOS\RestBundle\View\View;  
use FOS\RestBundle\Controller\Annotations\RouteResource;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use JMS\Serializer\SerializationContext;

/**
 * 
 * @RouteResource("Workspace")
 */
class WorkspacesController extends Controller
{
    public function getAction()
    {
        /* @var $caller User */
        $user = $this->container->get( 'security.context' )->getToken()->getUser();
        
        $workspace = $user->getWorkspace();
        
        if (!$workspace instanceof Workspace) {
            throw new NotFoundHttpException('Workspace not found');
        }
        
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode(200);
        $view->setData($workspace);  
        $view->setSerializationContext( $this->getContext( array("workspace") ));
        return $this->get('fos_rest.view_handler')->handle($view);
    }
    
    private function getContext( $groups ){
        $context = new SerializationContext();
        $context->setVersion("0");
        $context->setGroups($groups);
        
        return $context;
    }
}
?>
