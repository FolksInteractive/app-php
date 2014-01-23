var module = angular.module("RFP", ['ui.sortable','textAngular']);

module.controller("rfp.Controller", function($scope, form_name, RFPGuide, rfp){
        
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
        
        if ( $scope.isRFPEmpty() )
            $scope.rfp.body.push({ 'title' : '', 'body' : '' });
        
        $scope.rfpForm.$setDirty();
    }
    
    $scope.isRFPEmpty = function(){
        return (rfp.body.length <= 0);
    }
    
    $scope.form_name = form_name;
    
    $scope.rfp = rfp;   
    
    $scope.$watch("rfp.body", function(newValue, oldValue){
        if($scope.isRFPEmpty())
            RFPGuide.fill(rfp);
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

module.factory('RFPGuide', function($sanitize){

    function fill(rfp){
        if ( !rfp )
            return;

        rfp.body = [
            {
                'title' : 'Introduction',
                'body' : $sanitize('<strong>[ABC Inc.]</strong> is seeking the services of <strong>[an illustrator, a Web Designer, etc.]</strong> on a contract basis to produce a variety of <strong>[illustrative material, designs, etc.]</strong> for the <strong>[project 123]</strong>.')
            },
            
            {
                'title' : '[Illustrator, Web Design, etc.]',
                'body' : $sanitize('The required <strong>[illustrator, Web Designer, etc.]</strong> will need to be able to <strong>[develop illustrative materials (pixel or vector) for an e-Learning program]</strong>, using <strong>[Adobe Photoshop]</strong>.')
            },
            
            {
                'title' : 'Work Requirements',
                'body' : $sanitize('Ensure a consistent brand identity of <strong>[our company, a previous project, the following portfolio: www.ourcompany.com/portfolio]</strong>.')
            },
            
            {
                'title' : 'Expectations',
                'body' : $sanitize('<strong>[ABC Inc.]</strong> is requesting a quote including estimated costs and delivery dates in <strong>[January 2014]</strong> for the following: <ul><li><strong>[Requirement 1 – Description of the requirement]</strong>.</li><li><strong>[Requirement 2 – Description of the requirement]</strong>.</li><li><strong>[Requirement 3 – Description of the requirement]</strong>.</li></ul>')
            },
            
            {
                'title' : 'Estimated Project Duration',
                'body' : $sanitize('<strong>[ABC Inc.]</strong> required all <strong>[illustrative material]</strong> to be completed <strong>[before January 23th 2014]</strong>.')
            }
        ];
    }

    // Return Public API
    return {
        fill : fill
    };

});
