(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.project.orders.OrdersController",
        function($scope, $location, requestContext, ProjectManager, ProjectContext, Router){


            // --- Define Controller Methods. ------------------- //


            // --- Define Scope Methods. ------------------------ //
            $scope.openOrderCreatorModal = function(){
                $scope.showOrderCreatorModal = true;
            }

            $scope.closeOrderCreatorModal = function(){
                $scope.showOrderCreatorModal = false;
            }
        
            $scope.createOrder = function(){
                $scope.creatingOrder = true;
                return ProjectManager.createOrder($scope.project, $scope.newOrder)

                .success(function(order){
                    $scope.closeOrderCreatorModal();
                    $scope.creatingOrder = false;
                    $scope.newOrder = { };
                    return order;
                })

                .error(function(response){
                    $scope.creatingOrder = false;
                    return response;
                });
            }
            
            // I change the location when a order is select
            $scope.selectOrder = function(order){

                var routeParams = { }
                routeParams.idProject = ProjectContext.getProject().id;
                routeParams.idOrder = order.id;

                $location.path(
                    Router.getPath("workspace.project.orders.order", routeParams)
                );
            }

            $scope.showOrder = function(d){
                return ProjectContext.getOrderSection(d) == ProjectContext.getSection();
            }

            // --- Define Controller Variables. ----------------- //

            // Get the render context local to this controller (and relevant params).
            var renderContext = requestContext.getRenderContext("workspace.project.orders");


            // --- Define Scope Variables. ---------------------- //
           
           $scope.creatingOrder = false;
           
            // Store new order data
            $scope.newOrder = { };
            
            // I hold the selected order.
            $scope.order = ProjectContext.getOrder();
            $scope.$watch(ProjectContext.getOrder, function(newOrder){
                $scope.order = newOrder;
            })

            // The subview indicates which view is going to be rendered on the page.
            $scope.subview = renderContext.getNextSection();


            // --- Bind To Scope Events. ------------------------ //

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