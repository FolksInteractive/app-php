(function(ng, tcApp){

    tcApp.directive('tcPricebookItemCreatorModal',
        function(){

            return {
                restrict : 'E',
                    
                templateUrl : '/app/views/directives/pricebookitem-creator-modal.html',
                link : function(scope, element, attrs){
                    $("body").append(element);

                    //Watching in the PricebookItemController
                    scope.$watch('showPricebookItemCreatorModal', function(val){
                        if ( val ) {
                            $('#tcPricebookItemCreatorModal').modal('show');
                        } else {
                            $('#tcPricebookItemCreatorModal').modal('hide');
                        }
                    });

                    $('#tcPricebookItemCreatorModal').on('hidden', function(){
                        scope.$apply(function () {
                            scope.closePricebookItemCreatorModal();
                        });
                    });
                }

            }

        });
})(angular, tcApp);