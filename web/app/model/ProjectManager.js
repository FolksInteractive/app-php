(function(ng, tcApp){

    tcApp.factory('ProjectManager',
        function($http, $q, ProjectDAO, OrderDAO){

            function createOrder(project, order){

                return OrderDAO.post(order, project.id)

                    .success(function(order){
                    project.orders.unshift(order);

                    return order;
                })

                    .error(function(response){
                    return response;
                })
            }


            function updateOrder(project, order, values){

                // Update the short version order in the project object
                var shortOrder = findOrder(project, order.id);
                ng.extend(shortOrder, values);

                return OrderDAO.put(order.id, project.id, values)

                    .success(function(order){
                    ng.extend(shortOrder, order);
                    return order;
                })

                    .error(function(response){
                    return response;
                })
            }


            function getOrder(project, idOrder){
                return OrderDAO.get(idOrder, project.id)
                    .success(function(order){
                    return order;
                })

                    .error(function(response){
                    return response;
                })
            }


            function findOrder(project, idOrder){
                var order = { };

                angular.forEach(project.orders, function(d){
                    if ( d.id == idOrder ) {
                        order = d;
                    }
                });

                return order;
            }


            function purchaseOrder(project, order){
                var shortOrder = findOrder(project, order.id);
                shortOrder.approved = true;
                order.approved = true;
                return $http({
                    method : "POST",
                    url : '//timecrumbs/app_dev.php/api/projects/' + project.id + '/orders/' + order.id + "/approve",
                    data : { }
                });
            }

            function findDeliverable(order, idDeliverable){
                var deliverable = { };

                angular.forEach(order.deliverables, function(d){
                    if ( d.id == idDeliverable ) {
                        deliverable = d;
                    }
                });

                return deliverable;
            }


            function orderIsPurchasable(project, order){
                return (order.value && order.value >= 0 && order.offer != null);
            }

            function updateDeliverable(project, order, deliverable){
                //Make sure that the cost of de deliverable is a number
                if ( ng.isString(deliverable.cost) )
                    deliverable.cost = parseFloat(deliverable.cost);

                //Make sure that the quantity of de deliverable is a number
                if ( ng.isString(deliverable.quantity) )
                    deliverable.quantity = parseFloat(deliverable.quantity);


                // Sends to server
                return $http({
                    method : "PUT",
                    url : '//timecrumbs/app_dev.php/api/projects/' + project.id + '/orders/' + order.id + "/deliverables/" + deliverable.id,
                    data : {
                        "deliverable" : deliverable
                    }
                })

                    // On success
                    .success(function(d){
                    ng.extend(deliverable, d);
                });

            }

            function addDeliverable(project, order, deliverable){

                //Make sure that the cost of de deliverable is a number
                if ( ng.isString(deliverable.cost) )
                    deliverable.cost = parseFloat(deliverable.cost);

                // Sends to server
                return $http({
                    method : "POST",
                    url : '//timecrumbs/app_dev.php/api/projects/' + project.id + '/orders/' + order.id + "/deliverable",
                    data : {
                        "deliverable" : deliverable
                    }
                })

                    // On success
                    .success(function(d){
                    ng.extend(deliverable, d);
                });
            }


            function removeDeliverable(project, order, deliverable){
                // Sends to server
                return $http({
                    method : "DELETE",
                    url : '//timecrumbs/app_dev.php/api/projects/' + project.id + '/orders/' + order.id + "/deliverables/" + deliverable.id,
                });
            }


            function deliverableIsValid(deliverable){
                if ( deliverable.name == null || deliverable.name == "" )
                    return false;

                if ( deliverable.quantity == null || isNaN(deliverable.quantity) || deliverable.quantity <= 0 )
                    return false;

                if ( deliverable.cost == null || isNaN(deliverable.cost) || deliverable.cost < 0 )
                    return false;

                return true;
            }

            function completeOrder(project, order){
                var shortOrder = findOrder(project, order.id);
                shortOrder.completed = true;
                order.completed = true;
                return $http({
                    method : "POST",
                    url : '//timecrumbs/app_dev.php/api/projects/' + project.id + '/orders/' + order.id + "/complete",
                    data : { }
                });
            }

            function enroll(project, email, enrollAs){

                var data = { };
                var enrollTypes = ["client", "vendor", "collaborators"];
                if ( !ng.isString(email) || enrollTypes.indexOf(enrollAs) === -1 ) {
                    // We need to create a promise like the one returned by the 
                    // $http object (with the success and error methods)
                    // to stay consistent.
                    var promise = $q.reject("error with email");
                    // Defining success and error method for callbacks. 
                    // but it should never be called since the promise 
                    // is already rejected
                    promise.success = function(fn){
                        promise.then(function(response){
                            fn(response)
                        }, null);
                        return promise
                    };

                    promise.error = function(fn){
                        promise.then(null, function(response){
                            fn(response)
                        });
                        return promise;
                    };
                    return promise;
                }

                data[enrollAs] = (enrollAs != "collaborators") ? email : [email];

                return ProjectDAO.put(project.id, data)

                    .success(function(p){

                    ng.extend(project, p);

                    return p;
                })

                    .error(function(response){
                    return response;
                })
            }

            // ---------------------------------------------- //
            // ---------------------------------------------- //


            // Return the public API.

            return {
                createOrder : createOrder,
                updateOrder : updateOrder,
                updateDeliverable : updateDeliverable,
                addDeliverable : addDeliverable,
                removeDeliverable : removeDeliverable,
                deliverableIsValid : deliverableIsValid,
                getOrder : getOrder,
                findOrder : findOrder,
                purchaseOrder : purchaseOrder,
                orderIsPurchasable : orderIsPurchasable,
                enroll : enroll,
                completeOrder : completeOrder
            };


        });
})(angular, tcApp)