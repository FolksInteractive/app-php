(function(ng, tcApp){

    tcApp.directive('onBlur',
        function(){
            return {
                restrict : 'A',
                link : function(scope, element, attrs)
                {
                    element.bind('blur', function(){
                        scope.$apply(attrs.onBlur);
                    });
                }
            }
        });

})(angular, tcApp);