var module = angular.module("RFP", ['ui.sortable','textAngular']);

module.controller("rfp.Controller", function($scope, form_name, rfp){
        
    function getFormName(prop){
        return form_name+"["+prop+"]";
    };
    
    $scope.getFormName = function(prop){
        return getFormName(prop);
    };
    
    /* **************************************** */    
    /* ******        Body BLOCKS        ****** */
    /* **************************************** */
    
    function getBlockIndex( block ){
        return rfp.body.indexOf(block);
    };
    
    $scope.getBodyFormName = function(block, prop){
        var i = getBlockIndex(block);
        return getFormName('body')+"["+i+"]["+prop+"]";
    };
    
    $scope.addBlock = function(){
        //Add the new one to the list
        $scope.rfp.body.push({'title':'', 'body':''});
            
        $scope.rfpForm.$setDirty();
    };
    
    $scope.removeBlock = function(block){
        var i = getBlockIndex(block);
        $scope.rfp.body.splice(i,1);
        $scope.rfpForm.$setDirty();
    }
    
    $scope.isRFPEmpty = function(){
        return (rfp.body.length <= 0);
    }
    
    $scope.form_name = form_name;
    
    $scope.rfp = rfp;   
    
    $scope.$watch("rfp.body", function(newValue, oldValue){
        if($scope.isRFPEmpty())
            rfp.body.push({'title':'', 'body':''})
    });
    
    $scope.sortableOptions = {
        update: function() { 
            $scope.rfpForm.$setDirty();
        },
        helper: function(e, elem){
            return $(elem).clone().appendTo("body");
        }, 
        placeholder: "tc-placeholder-block",
        handle: ".tc-drag-block"
    };
});
