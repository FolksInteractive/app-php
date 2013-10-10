( function( ng, tcApp ) {
    
    tcApp.factory( 'ContactManager', 
    
    function( ContactDAO ) {
        
        function createContact( contactList, contact ) {
           
            return ContactDAO.post( contact )
            
                .success( function( contact ) {
                    contactList.contacts.unshift( contact );
                    
                    return contact;
                } )
                
                .error( function( response ) {
                    return response;
                } )
        }
                
        function updateContact( contactList, contact, values ) {
                
                // Update the short version contact in the contactList object
                var shortContact = findContact(contactList, contact.id);
                ng.extend(shortContact, values);                
                
                return ContactDAO.put( values, contact.id )
                
                    .success( function( contact ) { 
                        ng.extend(shortContact, contact);
                        return contact;
                    })
                    
                    .error( function( response ) {
                        return response;
                    });                    
        }
            
        
        function getContactList(  ) {
            return ContactDAO.getAll(  )
                .success( function( contactList ) {
                    return contactList;
                } )
                
                .error( function( response ) {
                    return response;
                } );
        }
        
        function getContact( idContact ) {
            return ContactDAO.get( idContact )
                .success( function( contact ) {
                    return contact;
                } )
                
                .error( function( response ) {
                return response;
                } );
        }

        function findContact( contactList, idContact ) {
            var contact = { };
            
            angular.forEach( contactList.contacts, function( c ) {
                if ( c.id == idContact ) {
                    contact = c;
                }
            } );
            
            return contact;
        }
        
        
 
        // ---------------------------------------------- //
        // ---------------------------------------------- //


        // Return the public API.
        
        return {
            createContact        : createContact,
            updateContact        : updateContact,
            getContact           : getContact,
            getContactList        : getContactList,
            findContact          : findContact
        };
        
        
    } );
} )( angular, tcApp )