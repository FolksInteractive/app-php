( function( ng, tcApp ) {

    tcApp.factory( 'ContactDAO',
            function( $http ) {
                
                var url = '//timecrumbs/app_dev.php/api/';
                return{
                    
                    getAll : function( id ) {
                        return  $http( {
                            method : "GET",
                            url : url+ "contacts" 
                        } );
                    },
                        
                    get : function( id ) {
                        return  $http( {
                            method : "GET",
                            url : url + 'contacts/' +  id
                        } );
                    },
                    post : function( contact ) {
                        return $http( {
                            method : "POST",
                            url : url + 'contact' ,
                            data : {
                                contact : contact
                            }
                        } );
                    },
                    put : function( values, id ) {
                        return $http( {
                            method : "PUT",
                            url : url + 'contacts/' +  id,
                            data : {
                                contact : values
                            }
                        } );
                    }
                }
            } );
            
} )( angular, tcApp )