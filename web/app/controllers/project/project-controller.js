tcApp.controller(
    "workspace.project.ProjectController",
    function($scope, $location, requestContext, ProjectManager, ProjectContext, Router){


        // --- Define Controller Methods. ------------------- //



        // --- Define Scope Methods. ------------------------ //

        $scope.getRoleInProject = function(){
            return ProjectContext.getRoleInProject();
        }
        
        $scope.isVendorInProject = function(){
            return ProjectContext.isVendorInProject();
        }
        
        $scope.isClientInProject = function(){
            return ProjectContext.isClientInProject();
        }
        
        $scope.isCollaboratorInProject = function(){
            return ProjectContext.isCollaboratorInProject();
        }

        // --- Define Controller Variables. ----------------- //

        // Get the render context local to this controller (and relevant params).
        var renderContext = requestContext.getRenderContext("workspace.project");


        // --- Define Scope Variables. ---------------------- //

        // Store project loading status
        $scope.isLoading = false;
        $scope.$watch(ProjectContext.isProjectLoading, function(value){
            $scope.isLoading = value;
        })

        // Store current project
        $scope.project = ProjectContext.getProject();

        // Store user position in the project (Client, Vendor, Collaborator);
        $scope.userIs = "vendor";


        // The subview indicates which view is going to be rendered on the page.
        $scope.subview = renderContext.getNextSection();


        // --- Bind To Scope Events. ------------------------ //
        
        $scope.$watch(ProjectContext.getProject, function(newProject){
            $scope.project = newProject;
        });
        
        // I handle changes to the request context.
        $scope.$on(
            "requestContextChanged",
            function(){

                // Make sure this change is relevant to this controller.
                if ( !renderContext.isChangeRelevant() ) {

                    return;

                }

                // Update the view that is being rendered.
                $scope.subview = renderContext.getNextSection();
            
            }
        );


        // --- Initialize. ---------------------------------- //


    }
);