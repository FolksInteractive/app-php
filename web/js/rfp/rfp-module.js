var module = angular.module("RFP", []);

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
        return getFormName('offer')+"["+i+"]["+prop+"]";
    };
    
    $scope.addBlock = function(){
        //Add the new one to the list
        $scope.rfp.body.push($scope.newBlock);
        
        //Resets newOffer
        $scope.newBlock = {'title':'', 'body':''};
    
        $scope.offerForm.$setDirty();
    };
    
    $scope.removeBlock = function(block){
        var i = getBlockIndex(block);
        $scope.rfp.body.splice(i,1);
        $scope.offerForm.$setDirty();
    }
    
    // Store new deliverables 
    $scope.newBlock = {'title':'', 'body':''};
    
    $scope.form_name = form_name;
    
    $scope.rfp = rfp;   
    
});
