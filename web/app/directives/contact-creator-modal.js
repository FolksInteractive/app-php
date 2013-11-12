(function(ng, tcApp){

    tcApp.directive('tcContactCreatorModal',
        function(){

            return {
                restrict : 'E',
                    
                templateUrl : '/app/views/directives/contact-creator-modal.html',
                link : function(scope, element, attrs){
                    $("body").append(element);

                    //Watching in the ContactController
                    scope.$watch('showContactCreatorModal', function(val){
                        if ( val ) {
                            $('#tcContactCreatorModal').modal('show');
                        } else {
                            $('#tcContactCreatorModal').modal('hide');
                        }
                    });

                    $('#tcContactCreatorModal').on('hidden', function(){
                        scope.$apply(function () {
                            scope.closeContactCreatorModal();
                        });
                    });
                }

            }

        });
})(angular, tcApp);