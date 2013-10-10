( function( ng, tcApp ) {
    
    tcApp.factory( 'PricebookManager', 
    
    function( PricebookDAO ) {
        
        function createItem( pricebook, item ) {
           
            return PricebookDAO.postItem( item )
            
                .success( function( item ) {
                    pricebook.items.unshift( item );
                    
                    return item;
                } )
                
                .error( function( response ) {
                    return response;
                } )
        }
                
        function updateItem( pricebook, item, values ) {
                
                // Update the short version contact in the pricebook object
                var shortItem = findItem(pricebook, item.id);
                ng.extend(shortItem, values);                
                
                return PricebookDAO.putItem( values, item.id )
                
                    .success( function( item ) { 
                        ng.extend(shortItem, item);
                        return item;
                    })
                    
                    .error( function( response ) {
                        return response;
                    });                    
        }
            
        
        function getPricebook(  ) {
            return PricebookDAO.get(  )
                .success( function( pricebook ) {
                    return pricebook;
                } )
                
                .error( function( response ) {
                    return response;
                } );
        }
        
        function getItem( idItem ) {
            return PricebookDAO.getItem( idItem )            
                .success( function( item ) {
                    return item;
                } )
                
                .error( function( response ) {
                return response;
                } );
        }

        function findItem( pricebook, idItem ) {
            var item = { };
            
            angular.forEach( pricebook.items, function( i ) {
                if ( i.id == idItem ) {
                    item = i;
                }
            } );
            
            return item;
        }
        
        
 
        // ---------------------------------------------- //
        // ---------------------------------------------- //


        // Return the public API.
        
        return {
            createItem      : createItem,
            updateItem      : updateItem,
            getItem         : getItem,
            getPricebook    : getPricebook,
            findItem        : findItem
        };
        
        
    } );
} )( angular, tcApp )