var module = angular.module("OrderEdit", []);

module.controller("order.EditController", function($scope, form_name, order){
    
    function getIndex( deliverable ){
        return order.deliverables.indexOf(deliverable);
    };
    
    function getFormName(prop){
        return form_name+"["+prop+"]";
    };
    
    $scope.getFormName = function(prop){
        return getFormName(prop);
    };
    
    $scope.getDeliverableFormName = function(deliverable, prop){
        var i = getIndex(deliverable);
        return getFormName('deliverables')+"["+i+"]["+prop+"]";
    };
    
    $scope.addDeliverable = function(){
        //Add the new one to the list
        $scope.order.deliverables.push($scope.newDeliverable);
        
        //Resets newDeliverable
        $scope.newDeliverable = {name:'', 'cost':null, 'quantity':1};
    
        $scope.deliverablesForm.$setDirty();
    };
    
    $scope.removeDeliverable = function(deliverable){
        var i = getIndex(deliverable);
        $scope.order.deliverables.splice(i,1);
        $scope.deliverablesForm.$setDirty();
    }
    
    $scope.getTotal = function(){
        var deliverable;
        var total = 0;
        for( var i in $scope.order.deliverables ){
            deliverable = $scope.order.deliverables[i];
            total = deliverable.quantity * deliverable.cost;
        }
        return total;
    }
    
    $scope.form_name = form_name;
    
    $scope.order = order;
    
    // Store new deliverables 
    $scope.newDeliverable = {name:'', 'cost':null, 'quantity':1};
    
});