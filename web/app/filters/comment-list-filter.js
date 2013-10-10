(function(ng, tcApp){

    tcApp.filter('commentsfilter',
        function( ){
            
            var oldComments = null;
            
            // This filter shows only recentComments
            return function(comments, hideOlder, nb){
                
                if( !comments )
                    return;
                
                if(hideOlder)
                    return comments.slice(comments.length-nb,comments.length);
                
                return comments
            }

        }

    );

})(angular, tcApp);