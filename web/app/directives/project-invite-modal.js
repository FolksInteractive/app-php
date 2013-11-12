(function(ng, tcApp){

    tcApp.directive('tcProjectInviteModal',
        function(){

            return {
                restrict : 'E',
                templateUrl : '/app/views/directives/project-invite-modal.html',
                link : function(scope, element, attrs){
                    $("body").append(element);

                    //Watching in the WorkspaceController
                    scope.$watch('showProjectInviteModal', function(val){
                        if ( val ) {
                            $('#tcProjectInviteModal').modal('show');
                        } else {
                            $('#tcProjectInviteModal').modal('hide');
                        }
                    });

                    scope.getHeaderLabel = function(){
                        var l = "Invite";

                        switch ( scope.enrollAs ) {
                            case 'vendor' :
                                l += " Vendor"
                                break;
                            case 'client' :
                                l += " Client"
                                break;
                            case 'collaborators' :
                                l += " Collaborator"
                                break;
                        }
                        return l;
                    }

                    // When the user click the close button...
                    $('#tcProjectInviteModal').on('hidden', function(){
                        scope.$apply(function(){
                            scope.closeProjectInviteModal();
                        });
                    });

                    $('#tcProjectInviteModal').on('show', function(){
                        //
                    });
                }

            }

        });
})(angular, tcApp);