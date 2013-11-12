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
 * @RouteResource("User")
 */
class UsersController extends Controller
{
    /**
     * @param type $id
     * @return type
     * @throws NotFoundHttpException
     */
    public function getAction($id)
    {
        /* @var $caller User */
        $workspace = $this->container->get( 'security.context' )->getToken()->getUser()->getWorkspacE();
        
        $collaborator = $this->container->get("tc.manager.workspace")->findCollaborator($workspace, $id);
        
        if (!$collaborator instanceof User) {
            throw new NotFoundHttpException('User not found');
        }
        
        /* @var $view View */
        $view = View::create();
        $view->setStatusCode(200);
        $view->setData($collaborator);  
        $view->setSerializationContext( $this->getContext( array("user") ));
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
