(function(ng, module){

    module.directive("order.discuss.tcThread",
        function($document, $window, order){
            return{
                restrict : "C",
                link : function(scope, element, attrs){
                    scope.$watch(
                        function(){
                            return order.thread.comments
                        },
                        function(newComments, oldComments){
                            if ( !newComments || newComments == oldComments )
                                return;

                            if ( newComments.length == oldComments.length )
                                return;

                            $document.scrollTop($document.height());
                        },
                        true
                    );
                }
            }
        }
    );
})(angular, module);