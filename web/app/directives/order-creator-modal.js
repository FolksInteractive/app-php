(function(ng, tcApp){

    tcApp.directive('tcOrderCreatorModal',
    
        function(){
            return {
                restrict : 'E',                    
                templateUrl : '/app/views/directives/order-creator-modal.html',
                
                link : function(scope, element, attrs){
                    $("body").append(element);
                    //Watching in the OrderController
                    scope.$watch('showOrderCreatorModal', function(val){
                        if ( val ) {
                            $('#tcOrderCreatorModal').modal('show');
                        } else {
                            $('#tcOrderCreatorModal').modal('hide');
                        }
                    });

                    $('#tcOrderCreatorModal').on('hidden', function(){
                        scope.$apply(function () {
                            scope.closeOrderCreatorModal();
                        });
                    });
                    
                }

            }

        });
})(angular, tcApp);