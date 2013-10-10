(function(ng, tcApp){
    tcApp.controller(
        "workspace.ManagementMenuController",
        function($scope, requestContext, ProjectContext, Router){


            // --- Define Controller Methods. ------------------- //
            

            // --- Define Scope Methods. ------------------------ //

            $scope.getHref = function(routeName){
                return "#"+Router.getPath( routeName , { });
            }

            // --- Define Controller Variables. ----------------- //

            
            // --- Define Scope Variables. ---------------------- //

            $scope.currentSection = $scope.subview || "dashboard";
            
            // --- Bind To Scope Events. ------------------------ //


            // --- Initialize. ---------------------------------- //

            

        }
    );
})(angular, tcApp);