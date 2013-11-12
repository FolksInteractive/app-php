(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.contacts.ContactsController",
        function($scope, $location, requestContext, ContactContext, ContactManager, Router){


            // --- Define Controller Methods. ------------------- //

            
            // --- Define Scope Methods. ------------------------ //
            $scope.openContactCreatorModal = function(){
                $scope.showContactCreatorModal = true;
            }

            $scope.closeContactCreatorModal = function(){
                $scope.showContactCreatorModal = false;
            }
        
            $scope.createContact = function(){
                $scope.isSavingNewContact = true;
                return ContactManager.createContact($scope.contactList, $scope.newContact)

                .success(function(contact){
                    $scope.closeContactCreatorModal();
                    $scope.isSavingNewContact = false;
                    $scope.newContact = { };
                    ContactContext.setContact(contact); 
                    return contact;
                })

                .error(function(response){
                    $scope.isSavingNewContact = false;
                    return response;
                });
            }
        
            $scope.selectContact = function(contact){

                $location.path(
                    Router.getPath("workspace.contacts.contact", {idContact : contact.id })
                );
            };
            
            // --- Define Controller Variables. ----------------- //

            // Get the render context local to this controller (and relevant params).
            var renderContext = requestContext.getRenderContext("workspace.contacts");


            // --- Define Scope Variables. ---------------------- //
            
            // Store contact list
            $scope.contactList = ContactContext.getContactList();
            
            // Store current project
            $scope.contact = ContactContext.getContact();

            // Store new contact data
            $scope.newContact = { };
        
            // Store saving new contact status
            $scope.isSavingNewContact = false;
            
            // Store loading contact status
            $scope.isContactLoading = ContactContext.isContactLoading();
            
            // Flag to show the contact creator modal
            $scope.showContactCreatorModal = false;
        
            // The subview indicates which view is going to be rendered on the page.
            $scope.subview = renderContext.getNextSection();


            // --- Bind To Scope Events. ------------------------ //
            
            $scope.$watch(ContactContext.getContactList, function(newContactList){
                $scope.contactList = newContactList;
            });
                        
            $scope.$watch(ContactContext.getContact, function(newContact){
                $scope.contact = newContact;
            });
                        
            $scope.$watch(ContactContext.isContactLoading, function(value){
                $scope.isContactLoading = value;
            });
            
            // I handle changes to the request context.
            $scope.$on(
                "requestContextChanged",
                function(){

                    // Make sure this change is relevant to this controller.
                    if ( !renderContext.isChangeRelevant() ) {
                        return;
                    }

                    // Update the view that is being rendered.
                    $scope.subview = renderContext.getNextSection();
                }
            );


        }
    );

})(angular, tcApp);