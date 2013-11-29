var module = angular.module("Order", []);

module.controller("order.Controller", function($scope, form_name, order){
    
    $scope.form_name = form_name;
    
    function getFormName(prop){
        return form_name+"["+prop+"]";
    };
    
    $scope.getFormName = function(prop){
        return getFormName(prop);
    };
    
    /* **************************************** */    
    /* ******        OFFER BLOCKS        ****** */
    /* **************************************** */
    
    function getBlockIndex( block ){
        return order.offer.indexOf(block);
    };
    
    $scope.getOfferFormName = function(block, prop){
        var i = getBlockIndex(block);
        return getFormName('offer')+"["+i+"]["+prop+"]";
    };
    
    $scope.addBlock = function(){
        //Add the new one to the list
        $scope.order.offer.push({'title':'', 'body':''});
    
        $scope.offerForm.$setDirty();
    };
    
    $scope.removeBlock = function(block){
        var i = getBlockIndex(block);
        $scope.order.offer.splice(i,1);
        $scope.offerForm.$setDirty();
    }
    
    $scope.offerIsEmpty = function(){
        return order.offer.length<=0;
    }
    
    $scope.order = order;
    
    $scope.$watch("order.offer", function(newValue, oldValue){
        if($scope.offerIsEmpty())
            order.offer.push({'title':'', 'body':''})
    });
        
    /* **************************************** */    
    /* ******        DELIVERABLES        ****** */
    /* **************************************** */
    
    function getDeliverableIndex( deliverable ){
        return order.deliverables.indexOf(deliverable);
    };
    
    $scope.getDeliverableFormName = function(deliverable, prop){
        var i = getDeliverableIndex(deliverable);
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
        var i = getDeliverableIndex(deliverable);
        $scope.order.deliverables.splice(i,1);
        $scope.deliverablesForm.$setDirty();
    }
    
    $scope.deliverablesIsEmpty = function(){
        return order.deliverables.length<=0;
    }
    
    // Store new deliverables 
    $scope.newDeliverable = {name:'', 'cost':null, 'quantity':1};
    /* *************************************** */
    
    $scope.getTotal = function(){
        var deliverable;
        var total = 0;
        for( var i in $scope.order.deliverables ){
            deliverable = $scope.order.deliverables[i];
            total = deliverable.quantity * deliverable.cost;
        }
        return total;
    }
    
    
    
    
});
