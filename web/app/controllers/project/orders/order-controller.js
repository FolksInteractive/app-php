(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.project.orders.OrderController",
        function($scope, ProjectContext, ProjectManager){


            // --- Define Controller Methods. ------------------- //

            function switchView(viewName){
                $scope.subview = viewName;
            }
            
            // --- Define Scope Methods. ------------------------ //

            $scope.openOrder = function(){
                if ( !$scope.order.deliverables )
                    $scope.order.deliverables = [];

                switchView("view");
            }

            $scope.openDiscussion = function(){
                switchView("discussion");
            }

            $scope.enterEditMode = function(){
                $scope.openOrder();
                $scope.editMode = true;
            }

            $scope.exitEditMode = function(action){
                //The possible actions are save or cancel (default)
                action = action || "cancel";
                
                // Trigger an event for the OrderEditController since the 
                // toolbar is not in the same scope
                var e = $scope.$broadcast("ExitEditMode",{"action": action})
                if(!e.defaultPrevented)
                    $scope.editMode = false;
            }

            $scope.markCompleted = function(){
                ProjectManager.completeOrder(
                    ProjectContext.getProject(),
                    $scope.order);
            }

            $scope.purchase = function(){
                ProjectManager.purchaseOrder(
                    ProjectContext.getProject(),
                    $scope.order);

            }

            $scope.isPurchasable = function(){
                if ( !$scope.order )
                    return false;

                return ($scope.agreement.accept &&
                    ProjectManager.orderIsPurchasable(
                    ProjectContext.getProject(),
                    $scope.order));
            }

            $scope.getOrderTotal = function(){
                var total = 0;
                ng.forEach($scope.order.deliverables, function(d){
                    total += d.cost * d.quantity;
                });

                return (total > 0) ? total : 0;
            }


            // --- Define Controller Variables. ----------------- //


            // --- Define Scope Variables. ---------------------- //

            // I hold the order to render.
            $scope.order = ProjectContext.getOrder();
            $scope.$watch(ProjectContext.getOrder, function(newOrder){
                $scope.editMode = false;
                $scope.order = newOrder;

                // If no order is selected, reset view to default
                if ( !newOrder ) {
                    switchView("");
                } else {
                    switchView("discussion");
                }

            });

            //$scope.$watch("order.deliverables", function(newOne,oldOne){console.log(newOne)}, true)

            // I flag when a order is loading
            $scope.isLoading = ProjectContext.isOrderLoading();
            $scope.$watch(ProjectContext.isOrderLoading, function(value){
                $scope.isLoading = value;
            })

            // I hold the client decision if y agrees the agreement (checkbox)
            $scope.agreement = { accept : false };

            // I help to manage views for estimation
            $scope.editMode = false;

            $scope.subview = "";
        }
    );

})(angular, tcApp);