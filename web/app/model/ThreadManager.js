( function( ng, tcApp ) {
    
    tcApp.factory(
            'ThreadManager',
            function( OrderDAO ) {
                
                function addComment ( comment, order ) {
                        
                    order.thread.comments.push( comment );
                        
                    return OrderDAO.postComment( comment, order.id, order.project.id )

                        .success( function( c ) {
                            angular.extend( comment, c );
                            return comment;
                        } )

                        .error( function( response ) {
                            return response;
                        } );
                }                
 
                // ---------------------------------------------- //
                // ---------------------------------------------- //


                // Return the public API.
                
                return {
                    addComment : addComment
                };
            } 
        )
            
} )( angular, tcApp )