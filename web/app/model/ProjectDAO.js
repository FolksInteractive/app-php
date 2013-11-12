( function( ng, tcApp ) {
    tcApp.factory( 
            'ProjectDAO',
            function( $http ) {
                var url = '//timecrumbs/app_dev.php/api/';
                return {
                    get : function( id ) {
                        return  $http( {
                            method : "GET",
                            url : url + 'projects/' + id
                        } );
                    },
                    post : function( project ) {
                        return $http( {
                            method : "POST",
                            url : url + 'project',
                            data : {
                                project : project
                            }
                        } );
                    },
                    put : function( id, data ) {
                        return $http( {
                            method : "PUT",
                            url : url + "projects/" + id ,
                            data : {
                                project : data
                            }
                        } );
                    }
                }
            } )
} )( angular, tcApp )