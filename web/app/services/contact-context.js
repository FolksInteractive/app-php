(function(ng, tcApp){
    tcApp.service("ContactContext",
        function($rootScope, $location, requestContext, Router, ContactManager){

            // I initialize data based on the request params
            function loadData(){
                // Store contact ID
                var idContact = requestContext.getParam("idContact");

                // If idContact is specified
                if ( idContact != null && (contact == null || idContact != contact.id) ) {

                    // Temporary short version of the selected contact
                    contact = ContactManager.findContact($rootScope.workspace.contact_list, idContact);
                    
                    // Load contact data
                    loadContact(idContact);

                } else if ( idContact == null ) {
                    contact = null;
                }
            }

            // I load contact data
            function loadContact(idContact){
                contactLoading = true;
                return ContactManager.getContact(idContact)
                    .success(function(response){

                    // Store loaded contact to context
                    contact = response;
                    contactLoading = false;

                    return contact;
                })
                    .error(function(response){

                    // Reset order object
                    contact = null;

                    // Update order loading status
                    contactLoading = false;

                    // If no order found redirect to contact list
                    $location.path(
                        Router.getPath("workspace.contacts")
                        );
                });
            }


            function getContactList(){
                return contactList;
            }

            function getContact(){
                return contact;
            }

            function setContact( c ){
                contact = c;
                $location.path(
                    Router.getPath("workspace.contacts.contact",{idContact:c.id})
                );
            }
            
            function isContactLoading(){
                return contactLoading;
            }

            // ---------------------------------------------- //
            // ---------------------------------------------- //

            // CONSTANT

            // Store the contact list
            var contactList = $rootScope.workspace.contact_list;

            // Store the current contact, can be null
            var contact = null;

            // Store contact loading status
            var contactLoading = false;


            // ---------------------------------------------- //
            // ---------------------------------------------- //

            $rootScope.$on("requestContextChanged", loadData);


            loadData();

            // ---------------------------------------------- //
            // ---------------------------------------------- //


            // Return the public API.
            return({
                getContactList : getContactList,
                getContact : getContact,
                setContact : setContact,
                isContactLoading : isContactLoading
            });


        });
})(angular, tcApp)