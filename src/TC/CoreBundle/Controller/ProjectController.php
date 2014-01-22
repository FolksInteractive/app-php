<?php

namespace TC\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use TC\CoreBundle\Entity\Project;
use TC\CoreBundle\Form\ProjectType;

/**
 * Project controller.
 *
 * @Route("/projects")
 */
class ProjectController extends Controller
{
    /**
     * Coming soon page for project section.
     *
     * @Route("/", name="project")
     * @Method("GET")
     * @Template("TCCoreBundle:Project:soon.html.twig")
     */
    public function soonAction(Request $request)
    {
        $projects  = $this->getProjectManager()->findAll();        

        return array(
        );
    }
    
    /**
     * Listing of Project.
     *
     * Route("/", name="project")
     * @Method("GET")
     * @Template("TCCoreBundle:Project:project_index_client.html.twig")
     */
    public function indexAction(Request $request)
    {
        $projects  = $this->getProjectManager()->findAll();        

        return array(
            'projects' => $projects
        );
    }
    
    /**
     * Creates a new Project.
     *
     * Route("/", name="project_create")
     * @Method("POST")
     * @Template("TCCoreBundle:Project:project_create.html.twig")
     */
    public function createAction(Request $request)
    {
        $project  = $this->getProjectManager()->create();
        
        $form = $this->createProjectForm($project);
        $form->submit($request);

        if ($form->isValid()) {
            $this->getProjectManager()->save($project);

            return $this->redirect($this->generateUrl('project_edit', array('idProject' => $project->getId())));
        }

        return array(
            'project' => $project,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to create a new Project.
     *
     * Route("/new", name="project_new")
     * @Method("GET")
     * @Template("TCCoreBundle:Project:project_new.html.twig")
     */
    public function newAction()
    {
        $project  = $this->getProjectManager()->create();
        $form   = $this->createProjectForm($project);

        return array(
            'project' => $project,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Project.
     *
     * Route("/{id}/edit", name="project_edit")
     * @Method("GET")
     * @Template("TCCoreBundle:Project:project_edit.html.twig")
     */
    public function editAction($idProject)
    {
        $project = $this->getProjectManager()->find($idProject);

        $form = $this->createProjectForm($project);

        return array(
            'project'   => $project,
            'form'      => $form->createView()
        );
    }

    /**
     * Edits an existing Project.
     *
     * Route("/{id}", name="client_project_update")
     * @Method("PUT")
     * @Template("TCCoreBundle:Project:project_edit.html.twig")
     */
    public function updateAction(Request $request, $idProject)
    {
        $project = $this->getProjectManager()->find($idProject);

        $form = $this->createProjectForm($project);
        $form->submit($request);

        if ($form->isValid()) {
            $this->getProjectManager()->save($project);

            return $this->redirect($this->generateUrl('client_project_edit', array('idProject' => $idProject)));
        }

        return array(
            'project'   => $project,
            'form'      => $form->createView()
        );
    }
    
    /* ******************************** */
    /*              FORMS               */
    /* ******************************** */
    
    /**
     * Creates a form to create a Project.
     *
     * @param Project $project The project
     *
     * @return Form The form
     */
    protected function createProjectForm( $project ){
         $form = $this->createForm( new ProjectType(), $project, array(
            'action' => $this->generateUrl("client_project_create"),
            'method' => 'POST',
                ) );
        
        $form->add( 'submit', 'submit', array('label' => 'Create') );

        return $form;
    }
}
