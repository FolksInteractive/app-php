(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.contacts.ContactController",
        function($scope, ContactManager, ContactContext){


            // --- Define Controller Methods. ------------------- //
            
            function contactChanged(value, oldValue){
                
                if( !$scope.editMode )
                    return;
                
                var splittedExp =  this.exp.split(".");
                var argName = splittedExp[splittedExp.length-1];
                
                updatedValues[argName] = value;
            }

            // --- Define Scope Methods. ------------------------ //

            $scope.enterEditMode = function(){
                $scope.editMode = true;
            }

            $scope.exitEditMode = function(){
                updatedValues = {};
                $scope.editMode = false;
            }

            $scope.updateContact = function(ngForm){

                if ( ngForm.$dirty ) {

                    if ( ngForm.$valid ) {
                        
                        ContactManager.updateContact($scope.contactList, $scope.contact, updatedValues)
                            .success(function(contact){
                                return contact;
                            })

                            .error(function(response){
                                return response;
                            });
                            
                        
                        ngForm.$setPristine();
                    }
                }else{
                    // Bring back to read view
                    $scope.exitEditMode(); 
                }
                
            }

            // --- Define Controller Variables. ----------------- //
            
            var updatedValues={};

            // --- Define Scope Variables. ---------------------- //
            
            
            // I help to manage views for estimation
            $scope.editMode = false;


            // --- Bind To Scope Events. ------------------------ //
            $scope.$watch("contact.firstName", contactChanged, true);
            $scope.$watch("contact.lastName", contactChanged, true);
            $scope.$watch("contact.email", contactChanged, true);
            $scope.$watch("contact.companyName", contactChanged, true);
            $scope.$watch("contact.website", contactChanged, true);
        }
    );

})(angular, tcApp);