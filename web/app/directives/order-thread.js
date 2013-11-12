(function(ng, tcApp){

    tcApp.directive('tcOrderThread',
    
        function(){
            return {
                restrict : 'A',      
                templateUrl : '/app/views/project/orders/thread.html',
                
                link : function(scope, element, attrs){
                    
                    scope.$watch(getCommentsCount, function(countNow, countBefore){
                        
                        if( countNow == -1)
                            return;
                        
                        // If a new comment has been added
                        if( countNow > countBefore ){
                            var $commentsSection = element.find(".tc-comments-section");
                            
                            // Scroll down the comment list to see the last comment added
                            $commentsSection.animate({ scrollTop: $commentsSection.height() }, 1000);
                        }
                        
                    });
                    
                    function getCommentsCount(){
                        if( !scope.order )
                            return -1;
                        
                        return scope.order.thread.comments.length;
                    }
                }

            }

        });
})(angular, tcApp);