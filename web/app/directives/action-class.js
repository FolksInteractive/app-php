(function(ng, tcApp){

    tcApp.directive('actionClass',
        function($route, $compile){
            return {
                restrict : 'A',
                link : function(scope, element, attrs){
                    var currentClass = "";
                    
                    scope.$on(
                        "$routeChangeSuccess",
                        function(event){
                            if ( !$route.current.action )
                                return;

                            var oldClass = currentClass;
                            currentClass = $route.current.action.replace(/\./g,'-')
                            
                            if ( oldClass != currentClass ) {
                                element.removeClass(oldClass);
                                element.addClass(currentClass);
                            }
                        }
                    );
                }

            }
        });

})(angular, tcApp);