var module = angular.module("Bill", []);

module.controller("bill.Controller", function($scope, deliverables, form_name){
    
    $scope.getTotal = function(){
        var deliverable;
        var total = 0;
        for( var i in deliverables ){
            deliverable = deliverables[i];
            total += deliverable.total;
        }
        return total;
    }
    
    $scope.nbSelected = function(){
        var nb = 0;
        for( var i in deliverables ){
           if(deliverables[i].selected)
               nb++;
        }
        
        return nb;
    }
    
    $scope.deliverable_form_name = form_name+"[deliverables][]";
    $scope.deliverables = deliverables;
});