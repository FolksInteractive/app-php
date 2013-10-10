(function(ng, tcApp){

    tcApp.filter('contactTypeaheadFilter',
        function( $rootScope ){
            
            /**
             * 
             * @param string haystack The string to search in.
             * @param string needle The string to find
             * @returns boolean
             */
            function contains( haystack, needle ){
                return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
            }
            
            return function(contacts, query){
                if( !query )
                    return contacts;
                
                var result = [];
                
                for(i in contacts){
                    var contact = contacts[i];
                    var contactName = contact.firstName + " " + contact.lastName;
                    
                    if( contains(contactName, query) || contains(contact.email, query) )
                        result.push( contact );
                }
                return result;
            }

        }

    );

})(angular, tcApp);