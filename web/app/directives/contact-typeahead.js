(function(ng, tcApp){

    tcApp.directive('tcContactTypeahead',
        function($rootScope, $filter){
            return {
                restrict : 'E',
                transclude : true,
                scope : {
                    source : "=",
                    model : "="
                },
                controller : function($scope, $element, $attrs){
                    $scope.active = -1;
                    $scope.contacts = $filter("contactTypeaheadFilter")($scope.source, $scope.query);


                    $scope.checkahead = function(){
                        $scope.active = -1;

                        $scope.contacts = $filter("contactTypeaheadFilter")($scope.source, $scope.query);

                        $scope.open = (!$scope.contacts.length) ? false : true;
                        if ( !$scope.query )
                            $scope.open = false;                        
                    };

                    $scope.setahead = function(contact){
                        $scope.query = contact.email;
                        $scope.open = false;
                    };

                    $element.bind('keyup', function(e){
                        $scope.$apply(function(){
                            $scope.handleKeypress.call($scope, e.which);
                        });
                    });
                    
                    $scope.handleKeypress = function(key){
                        if( !$scope.open )
                            return;
                        
                        if ( key == 40 && $scope.active < $scope.contacts.length - 1 )
                            $scope.active += 1;

                        if ( key == 38 && $scope.active > 0 )
                            $scope.active -= 1;

                        if ( key == 38 && $scope.active < 0 )
                            $scope.active = $scope.contacts.length - 1;
                        
                        if ( key == 13 )
                            $scope.setahead($scope.contacts[$scope.active]);
                        
                        // Close on ESC
                        if ( key == 27 )
                            $scope.open = false;
                    };
                    
                    $scope.$watch("query", function(newValue, oldValue){
                       $scope.model = newValue; 
                    });
                },
                templateUrl : '/app/views/directives/contact-typeahead.html',
                replace : true
            };
        });

    tcApp.filter('contactTypeaheadFilter',
        function($rootScope){

            /**
             * 
             * @param string haystack The string to search in.
             * @param string needle The string to find
             * @returns boolean
             */
            function contains(haystack, needle){
                return haystack.toLowerCase().indexOf(needle.toLowerCase()) !== -1;
            }

            return function(contacts, query){
                if ( !query )
                    return contacts;

                var result = [];

                for ( i in contacts ) {
                    var contact = contacts[i];
                    var contactName = contact.firstName + " " + contact.lastName;

                    if ( contains(contactName, query) || contains(contact.email, query) )
                        result.push(contact);
                }
                return result;
            }

        }

    );
})(angular, tcApp);