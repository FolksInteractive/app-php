(function(ng, tcApp){

    "use strict";

    tcApp.controller(
        "workspace.project.orders.OrderEditController",
        function($scope, ProjectContext, ProjectManager){


            // --- Define Controller Methods. ------------------- //

            function cancel(){
                //ng.extend()
            }

            function save(){
                // Bring back to read mode of the order
                $scope.exitEditMode();

                // We use updateOrder method for when we need to update 
                // properties like offer, and request. 
                // When modifying deliverables we need to use 
                // addDeliverable/removeDelivrable methods from the OrderManager
                var data = { };
                if ( orderBackup.offer != $scope.order.offer ) {
                    data.offer = $scope.order.offer;
                }

                if ( Object.keys(data).length > 0 ) {
                    // Pass data to manager
                    ProjectManager.updateOrder(
                        ProjectContext.getProject(),
                        $scope.order,
                        data
                        );
                }

                if ( dirtyDeliverables.length > 0 ) {
                    // Loop through the delivrables list and sends it to the manager
                    angular.forEach(dirtyDeliverables, function(deliverable){

                        ProjectManager.updateDeliverable(
                            ProjectContext.getProject(),
                            $scope.order,
                            deliverable
                            );

                    });

                    dirtyDeliverables = [];
                }

                if ( addedDeliverables.length > 0 ) {
                    // Loop through the delivrables list and sends it to the manager
                    angular.forEach(addedDeliverables, function(deliverable){

                        ProjectManager.addDeliverable(
                            ProjectContext.getProject(),
                            $scope.order,
                            deliverable
                            );
                    });

                    addedDeliverables = [];
                }

                if ( removedDeliverables.length > 0 ) {
                    // Loop through the delivrables list and sends it to the manager
                    angular.forEach(removedDeliverables, function(deliverable){

                        ProjectManager.removeDeliverables(
                            ProjectContext.getProject(),
                            $scope.order,
                            deliverable
                            );

                    });

                    removedDeliverables = [];
                }
            }


            // --- Define Scope Methods. ------------------------ //


            // Use in ng-change directive for editing a directive in order-edit.html
            $scope.updateDeliverable = function(deliverable){
                // Check if it is an persisted deliverable.
                // New deliverable will be updated automatically with binding
                if ( !deliverable.id )
                    return;

                // Check if an the deliverable is already dirty
                var index = dirtyDeliverables.indexOf(deliverable)
                if ( index === -1 ) {
                    dirtyDeliverables.push(deliverable);
                }
            }

            $scope.addDeliverable = function(){
                // Make sure the deliverable is valid before continuing
                if ( !ProjectManager.deliverableIsValid($scope.newDeliverable) )
                    return;

                // Add deliverable to current order
                $scope.order.deliverables.push($scope.newDeliverable);

                // Store deliverable for further commit
                addedDeliverables.push($scope.newDeliverable);

                // Resets new deliverable model
                $scope.newDeliverable = {
                    quantity : 1,
                    cost : 0
                };
            }

            $scope.removeDeliverable = function(deliverable){
                // Remove deliverable to current order
                var index = $scope.order.deliverables.indexOf(deliverable);
                if ( index !== -1 )
                    $scope.order.deliverables.splice(index, 1);

                // Check if it is a newly added deliverables.
                // If yes, we don't have to store it for later removal to the
                // server because no id is assign yet.
                // Instead, we remove from the added list
                var index = addedDeliverables.indexOf(deliverable)
                if ( index !== -1 ) {
                    addedDeliverables.splice(index, 1)
                } else if ( deliverable.id ) {
                    // Store deliverable for further update to server
                    removedDeliverables.push(deliverable);
                }

            }


            // --- Define Controller Variables. ----------------- //

            // I store removed deliverables
            var removedDeliverables = [];

            // I store added deliverables
            var addedDeliverables = [];

            // I store added deliverables
            var dirtyDeliverables = [];

            // I keep a backup of the current order to compare or cancel
            var orderBackup = ng.copy($scope.order);

            // --- Define Scope Variables. ---------------------- //

            // I store new deliverable infos
            $scope.newDeliverable = {
                quantity : 1,
                cost : 0
            };

            // We have to liste to the closing event because the submit button 
            // is not in the same scope of the controller
            $scope.$on("ExitEditMode", function(event, args){
                if ( args.action === "save" ) {
                    if ( $scope.editOrderForm.$valid ) {
                        save();
                    } else {
                        event.preventDefault();
                    }
                }
                if ( args.action === "cancel" ) {
                    cancel();
                }
            });
        }
    );

})(angular, tcApp);