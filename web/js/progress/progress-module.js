var module = angular.module("Progress", ['ui.bootstrap.datepicker']);

module.controller("progress.Controller", function($scope, deliverables, form_name){
    
    function getFormName(prop){
        return form_name+"["+prop+"]";
    };
    
    function getDeliverableIndex( deliverable ){
        return deliverables.indexOf(deliverable);
    };
    
    $scope.getDeliverableFormName = function(deliverable, prop){
        var i = getDeliverableIndex(deliverable);
        return getFormName('deliverables')+"["+i+"]["+prop+"]";
    };
    
    $scope.deliverables = deliverables;
    
    $scope.completeDeliverable = function(deliverable){
        if(deliverable.completed){
            deliverable.oldProgress = deliverable.progress;
            deliverable.progress = 100;
        }else{
            deliverable.progress = deliverable.oldProgress || 0;
        }
    };
    
    
    $scope.setProgress = function(deliverable, value){
        deliverable.progress = value;
        
        $scope.progressForm.$setDirty();
    }
});

module.controller("progress.DueAtController", function($scope, $timeout){    
    $scope.calendar = {
        opened : false,
        minDate : new Date(),
        maxDate : null,
        format : 'yyyy-MM-dd'
    };
});