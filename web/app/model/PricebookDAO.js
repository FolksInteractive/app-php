( function( ng, tcApp ) {

    tcApp.factory( 'PricebookDAO',
            function( $http ) {
                
                var url = '//timecrumbs/app_dev.php/api/';
                return{
                    
                    get : function( id ) {
                        return  $http( {
                            method : "GET",
                            url : url+ "pricebook" 
                        } );
                    },
                        
                    getItem : function( id ) {
                        return  $http( {
                            method : "GET",
                            url : url + 'pricebook/items/' +  id
                        } );
                    },
                    postItem : function( item ) {
                        return $http( {
                            method : "POST",
                            url : url + 'pricebook/item' ,
                            data : {
                                pricebookitem : item
                            }
                        } );
                    },
                    putItem : function( values, id ) {
                        return $http( {
                            method : "PUT",
                            url : url + 'pricebook/items/' +  id,
                            data : {
                                pricebookitem : values
                            }
                        } );
                    }
                }
            } );
            
} )( angular, tcApp )