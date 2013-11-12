tcApp.controller(
    "workspace.ShortcutMenuController",
    function($scope, $location, ProjectContext, Router){


        // --- Define Controller Methods. ------------------- //



        // --- Define Scope Methods. ------------------------ //
        
        $scope.openDashBoard = function(){
            $location.path(                 
                Router.getPath("workspace.dashboard", {"idProject":project.id}) 
            );
        }
        
        $scope.openProject = function( project ) {
            
            if( !project || project == null || angular.isUndefined(project) )
                return;
            
            $location.path(                 
                Router.getPath("workspace.project.orders", {"idProject":project.id}) 
            );
        }
        
        // --- Define Controller Variables. ----------------- //

        
        // --- Define Scope Variables. ---------------------- //
        
        $scope.project = ProjectContext.getProject();
        $scope.$watch(ProjectContext.getProject, function(newProject){
            $scope.project = newProject;
        });
        
        // --- Bind To Scope Events. ------------------------ //

        


        // --- Initialize. ---------------------------------- //


    }
);