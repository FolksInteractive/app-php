(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.pricebook.PricebookItemController",
        function($scope, PricebookManager){


            // --- Define Controller Methods. ------------------- //


            // --- Define Scope Methods. ------------------------ //

            $scope.enterEditMode = function(){
                $scope.editMode = true;
            }

            $scope.exitEditMode = function(){
                $scope.editMode = false;
            }
            
            $scope.updateItem = function(){
                
            }

            // --- Define Controller Variables. ----------------- //


            // --- Define Scope Variables. ---------------------- //
            
            // I help to manage views for estimation
            $scope.editMode = false;


            // --- Bind To Scope Events. ------------------------ //


        }
    );

})(angular, tcApp);