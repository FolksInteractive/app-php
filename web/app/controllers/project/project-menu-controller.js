(function(ng, tcApp){
    tcApp.controller(
        "workspace.project.ProjectMenuController",
        function($scope, requestContext, ProjectContext, Router){


            // --- Define Controller Methods. ------------------- //
             
             
            // --- Define Scope Methods. ------------------------ //
            
            $scope.getHref = function(routeName){
                if( $scope.project == null )
                    return "#";
                
                return "#"+Router.getPath( routeName , { idProject : $scope.project.id });
            }

            // --- Define Controller Variables. ----------------- //

            // Get the render context local to this controller (and relevant params).
            var renderContext = requestContext.getRenderContext("workspace.project");


            // --- Define Scope Variables. ---------------------- //

            $scope.currentSection = renderContext.getNextSection() || "orders";
                        
            $scope.project = ProjectContext.getProject();
            $scope.$watch(ProjectContext.getProject, function(newProject){
                $scope.project = newProject;
            });
            // --- Bind To Scope Events. ------------------------ //


            // --- Initialize. ---------------------------------- //

            // I handle changes to the request context.
            $scope.$on(
                "requestContextChanged",
                function(){

                    // Make sure this change is relevant to this controller.
                    if ( renderContext.isChangeRelevant() ) {
                        $scope.currentSection = renderContext.getNextSection() || "orders";
                    }
                }
            );

        }
    );
})(angular, tcApp);