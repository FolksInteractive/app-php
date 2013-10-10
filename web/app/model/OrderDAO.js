( function( ng, tcApp ) {

    tcApp.factory( 'OrderDAO',
            function( $http ) {
                
                var url = '//timecrumbs/app_dev.php/api/projects';
                return{
                    get : function( id, idProject ) {
                        return  $http( {
                            method : "GET",
                            url : url + '/' + idProject + '/orders/' + id
                        } );
                    },
                    post : function( order, idProject ) {
                        return $http( {
                            method : "POST",
                            url : url + '/' + idProject + '/order',
                            data : {
                                order : order
                            }
                        } );
                    },
                    postComment : function( comment, idOrder, idProject ) {
                        return $http( {
                            method : "POST",
                            url : url + '/' + idProject + '/orders/' + idOrder + "/comment",
                            data : {
                                comment : comment
                            }
                        } );
                    },
                    put : function( idOrder, idProject, data ) {
                        return $http( {
                            method : "PUT",
                            url : url + '/' + idProject + '/orders/' + idOrder,
                            data : {
                                order : data
                            }
                        } );
                    }
                }
            } );
            
} )( angular, tcApp )