(function(ng, tcApp){

    tcApp.directive('tcProjectWizardModal',
        function(){

            return {
                restrict : 'E',
                controller : 'workspace.ProjectWizardController',
                templateUrl : '/app/views/directives/project-wizard-modal.html',
                link : function(scope, element, attrs){ 
                    $("body").append(element);      
                    
                    //Watching in the WorkspaceController
                    scope.$watch('showProjectWizardModal', function(val){
                        if(val){
                            $('#tcProjectWizardModal').modal('show');
                        }else{
                            $('#tcProjectWizardModal').modal('hide');
                        }
                    });
                    
                    // When the user click the close button...
                    $('#tcProjectWizardModal').on('hidden', function(){
                        scope.$apply(function () {
                            scope.closeProjectWizardModal();
                        });
                    });
                    
                    $('#tcProjectWizardModal').on('show', function(){
                        scope.initProjectWizard();
                    });
                }

            }

        });
})(angular, tcApp);