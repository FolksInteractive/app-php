tcApp.controller(
    "workspace.WorkspaceController",
    function($scope, $location, requestContext, Router, WorkspaceManager){


        // --- Define Controller Methods. ------------------- //



        // --- Define Scope Methods. ------------------------ //
        
        $scope.openProject = function( project ) {
            
            if( !project || project == null || angular.isUndefined(project) )
                return;
            
            $location.path(                 
                Router.getPath("workspace.project.orders", {"idProject":project.id}) 
            );
        }
        
        $scope.openProjectWizardModal = function(){
            $scope.showProjectWizardModal = true;
        }
        
        $scope.closeProjectWizardModal = function(){
            $scope.showProjectWizardModal = false;
        }
        
        $scope.getSubviewUrl = function(){
            var url = '/app/views'
            switch($scope.subview){
                case "dashboard" :
                    return url+'/workspace/dashboard.html';
                
                case "pricebook" :
                    return url+"/pricebook/index.html"
                    
                case "contacts" :
                    return url+'/contacts/index.html';
                
                case "project" :
                    return url+"/project/index.html"
            }
        }
        
        // --- Define Controller Variables. ----------------- //

        // Get the render context local to this controller (and relevant params).
        var renderContext = requestContext.getRenderContext("workspace");


        // --- Define Scope Variables. ---------------------- //
        
        // Flag to show the create project modal
        $scope.showProjectWizardModal = false;
                
        // The subview indicates which view is going to be rendered on the page.
        $scope.subview = renderContext.getNextSection();


        // --- Bind To Scope Events. ------------------------ //

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