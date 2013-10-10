tcApp.controller(
    "workspace.ProjectWizardController",
    function($scope, $location, WorkspaceManager, Router){
        
        $scope.initProjectWizard = function(){
            $scope.newProject = {};
            $scope.newProject.collaborator_emails = [];
            $scope.creating = false;
            $scope.step = 1;
        };
        
        $scope.isFirstStep = function(){
            return $scope.step === 1;
        };

        $scope.isLastStep = function(){
            return $scope.step === $scope.nbSteps;
        };

        $scope.isCurrentStep = function(step){
            return $scope.step === step;
        };

        $scope.setCurrentStep = function(step){
            $scope.step = step;
        };

        $scope.getCurrentStep = function(){
            return "step" + $scope.step;
        };

        $scope.getNextLabel = function(){
            return ($scope.isLastStep()) ? 'Submit' : 'Next step';
        };

        $scope.handlePrevious = function(){
            $scope.step -= ($scope.isFirstStep()) ? 0 : 1;
        };

        $scope.handleNext = function(){
            if ( $scope.isLastStep() ) {
               createProject();
            } else {
                $scope.step += 1;
            }
        };
        
        createProject = function(){
            var p = {};
            
            p.name = $scope.newProject.name;
            p.description = $scope.newProject.description;
            
            if($scope.newProject.is_vendor){
                p.vendor = $scope.workspace.id;
                p.client = $scope.newProject.contact_email
            }else{
                p.client = $scope.workspace.id;
                p.vendor = $scope.newProject.contact_email;
            }
            
            $scope.creating = true;
            
            return WorkspaceManager.createProject( $scope.workspace, p )
                .success( function( project ) {
                    $scope.creating = false;
                    
                    $scope.newProject = { };
                    $scope.closeProjectWizardModal(); // defined in WorkspaceController
                    
                    $location.path(                 
                        Router.getPath("workspace.project.orders", {"idProject":project.id}) 
                    );
                    
                    return project;
                } )
                
                .error( function( response ) {
                    $scope.creating = false;
                    
                    return response;
                } );
        }

        // --- Scope Variables. ---------------------- //
        
        $scope.newProject = {};
        $scope.newProject.collaborator_emails = [];
        $scope.creating = false;
        $scope.nbSteps = 3;
        $scope.step = 1;
    }
);