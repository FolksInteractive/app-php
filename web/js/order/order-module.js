var module = angular.module("Order", ['ui.sortable', 'tc.directives.numbers', 'textAngular']);

module.controller("order.Controller", function($scope, form_name, OrderGuide, order){

    $scope.form_name = form_name;

    function getFormName(prop){
        return form_name + "[" + prop + "]";
    }
    ;

    $scope.getFormName = function(prop){
        return getFormName(prop);
    };

    /* **************************************** */
    /* ******        OFFER BLOCKS        ****** */
    /* **************************************** */

    function getBlockIndex(block){
        return order.offer.indexOf(block);
    }
    ;

    $scope.getOfferFormName = function(block, prop){
        var i = getBlockIndex(block);
        return getFormName('offer') + "[" + i + "][" + prop + "]";
    };

    $scope.addBlock = function(){
        //Add the new one to the list
        $scope.order.offer.push({ 'title' : '', 'body' : '' });

        $scope.offerForm.$setDirty();
    };

    $scope.removeBlock = function(block){
        var i = getBlockIndex(block);
        $scope.order.offer.splice(i, 1);
        
        if ( $scope.offerIsEmpty() )
            $scope.order.offer.push({ 'title' : '', 'body' : '' });
        
        $scope.offerForm.$setDirty();
    }

    $scope.offerIsEmpty = function(){
        return order.offer.length <= 0;
    }

    $scope.order = order;

    $scope.$watch("order.offer", function(newValue, oldValue){
        if ( $scope.offerIsEmpty() )
            OrderGuide.fill(order);
    });

    $scope.sortableOptions = {
        update : function(){
            $scope.offerForm.$setDirty();
        },
        helper : function(e, elem){
            return $(elem).clone().appendTo("body");
        },
        placeholder : "tc-placeholder-block",
        handle : ".tc-drag-block"
    };

    /* **************************************** */
    /* ******        DELIVERABLES        ****** */
    /* **************************************** */

    function getDeliverableIndex(deliverable){
        return order.deliverables.indexOf(deliverable);
    }
    ;

    $scope.getDeliverableFormName = function(deliverable, prop){
        var i = getDeliverableIndex(deliverable);
        return getFormName('deliverables') + "[" + i + "][" + prop + "]";
    };

    $scope.addDeliverable = function(){
        //Add the new one to the list
        $scope.order.deliverables.push($scope.newDeliverable);

        //Resets newDeliverable
        $scope.newDeliverable = { name : '', 'cost' : null, 'quantity' : 1 };

        $scope.deliverablesForm.$setDirty();
    };

    $scope.removeDeliverable = function(deliverable){
        var i = getDeliverableIndex(deliverable);
        $scope.order.deliverables.splice(i, 1);
        $scope.deliverablesForm.$setDirty();
    }

    $scope.deliverablesIsEmpty = function(){
        return order.deliverables.length <= 0;
    }

    // Store new deliverables 
    $scope.newDeliverable = { name : '', 'cost' : null, 'quantity' : 1 };
    /* *************************************** */

    $scope.getTotal = function(){
        var deliverable;
        var total = 0;
        for ( var i in $scope.order.deliverables ) {
            deliverable = $scope.order.deliverables[i];
            total += deliverable.quantity * deliverable.cost;
        }
        return total;
    }

});

module.factory('OrderGuide', function($sanitize){

    function fill(order){
        if ( !order )
            return;

        order.offer = [
            {
                'title' : 'Project Description',
                'body' : $sanitize('The mandate consist of <strong>[updating, creating, etc.]</strong> over a period of <strong>[delay]</strong>.')
            },
            
            {
                'title' : 'Work Specifications',
                'body' : $sanitize('Here is the list of specifications required for the <strong>[project name]</strong> : <ul><li><strong>[Every images will be created using Adobe Photoshop Version 6.0]</strong></li><li><strong>[Website will be coded using PHP]</strong></li><li><strong>[2 Rounds of reviews will be allowed]</strong></li></ul>')
            }
        ];
    }

    // Return Public API
    return {
        fill : fill
    };

});