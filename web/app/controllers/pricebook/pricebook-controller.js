(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.pricebook.PricebookController",
        function($scope, $location, requestContext, PricebookContext, PricebookManager, Router){


            // --- Define Controller Methods. ------------------- //

            
            // --- Define Scope Methods. ------------------------ //
            $scope.openPricebookItemCreatorModal = function(){
                $scope.showPricebookItemCreatorModal = true;
            }

            $scope.closePricebookItemCreatorModal = function(){
                $scope.showPricebookItemCreatorModal = false;
            }
        
            $scope.createItem = function(){
                $scope.isSavingNewItem = true;
                return PricebookManager.createItem($scope.pricebook, $scope.newItem)

                .success(function(item){
                    $scope.closePricebookItemCreatorModal();
                    $scope.isSavingNewItem = false;
                    $scope.newItem = { };
                    PricebookContext.setItem(item); 
                    return item;
                })

                .error(function(response){
                    $scope.isSavingNewItem = false;
                    return response;
                });
            }
        
            $scope.selectItem = function(item){

                $location.path(
                    Router.getPath("workspace.pricebook.item", {idItem : item.id })
                );
            };
            
            // --- Define Controller Variables. ----------------- //

            // Get the render context local to this controller (and relevant params).
            var renderContext = requestContext.getRenderContext("workspace.pricebook");


            // --- Define Scope Variables. ---------------------- //
            
            // Store pricebook
            $scope.pricebook = PricebookContext.getPricebook();
            
            // Store current project
            $scope.item = PricebookContext.getItem();

            // Store new order data
            $scope.newItem = { };
        
            // Store saving new pricebookItem status
            $scope.isSavingNewItem = false;
            
            // Store loading pricebookItem status
            $scope.isItemLoading = PricebookContext.isItemLoading();
            
            // Flag to show the pricebookItem creator modal
            $scope.showPricebookItemCreatorModal = false;
        
            // The subview indicates which view is going to be rendered on the page.
            $scope.subview = renderContext.getNextSection();


            // --- Bind To Scope Events. ------------------------ //
            
            $scope.$watch(PricebookContext.getPricebook, function(newPricebook){
                $scope.pricebook = newPricebook;
            });
                        
            $scope.$watch(PricebookContext.getItem, function(newItem){
                $scope.item = newItem;
            });
                        
            $scope.$watch(PricebookContext.isItemLoading, function(value){
                $scope.isItemLoading = value;
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