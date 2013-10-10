(function(ng, tcApp){
    tcApp.controller(
        "workspace.project.ProjectUserController",
        function($scope, ProjectContext, ProjectManager){


            // --- Define Controller Methods. ------------------- //

            // --- Define Scope Methods. ------------------------ //
            
            $scope.isVendorInProject = function(){
                return ProjectContext.isVendorInProject();
            }

            $scope.isClientInProject = function(){
                return ProjectContext.isClientInProject();
            }

            $scope.isCollaboratorInProject = function(){
                return ProjectContext.isCollaboratorInProject();
            }
        
            $scope.openProjectInviteModal = function(enrollAs){
                $scope.enrollAs = enrollAs || "client";
                $scope.showProjectInviteModal = true;
            }

            $scope.closeProjectInviteModal = function(){
                $scope.enrollAs = null;
                $scope.newEnrollment = { };
                $scope.showProjectInviteModal = false;
            }
        
            $scope.sendEnrollment = function(){
                $scope.sending = true;
                return ProjectManager.enroll($scope.project, $scope.newEnrollment.email, $scope.enrollAs)

                .success(function(project){
                    $scope.closeProjectInviteModal();
                    $scope.sending = false;
                    $scope.newEnrollment = { };
                    return project;
                })

                .error(function(response){
                    $scope.sending = false;
                    return response;
                });
            }
            
            // This functions calculates the difference between the number of 
            // collaborator with the maximum number available
            $scope.getNbFreeSpaces = function(){
                var diff = 9;
                if( $scope.project.collaborators )
                     diff = 9 - $scope.project.collaborators.length
                                  
                return new Array(diff);
            }
            
            $scope.getCollaboratorLabel = function(collaborator){
                if(!collaborator)
                    return "";
                
                return (collaborator.firstName) ? (collaborator.firstName +' ' + collaborator.lastName) : collaborator;
            }

            // --- Define Controller Variables. ----------------- //

            // --- Define Scope Variables. ---------------------- //

            // Store current project
            $scope.project = ProjectContext.getProject();
            $scope.$watch(ProjectContext.getProject, function(newProject){
                $scope.project = newProject;
            });

            
            // Store new enrollment data
            $scope.newEnrollment = { };
            $scope.enrollAs = null;

            // --- Bind To Scope Events. ------------------------ //


            // --- Initialize. ---------------------------------- //


        }
    );
})(angular, tcApp);