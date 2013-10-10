(function(ng, tcApp){
    
    tcApp.directive('tooltip', 
    
    function(){
        return {
            restrict : 'A',
            link : function(scope, element, attrs)
            {
                $(element)
                    .attr('title', scope.$eval(attrs.tooltip))
                    .tooltip({ placement : attrs.tooltipPlacement });
            }
        }
    });
    
})(angular, tcApp);