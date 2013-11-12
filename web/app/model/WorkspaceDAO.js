( function( ng, tcApp ) {
    tcApp.factory(
            'WorkspaceDAO',
            function( $http ) {
                
                var url = '//timecrumbs/app_dev.php/api/workspace';
                return {
                    get : function( ) {
                        return  $http( {
                            method : "GET",
                            url : url
                        } );
                    }
                }
            } )
            
} )( angular, tcApp )