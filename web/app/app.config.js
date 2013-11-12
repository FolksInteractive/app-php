(function(ng, tcApp){

// Check if the response is a 401 error to redirect to login
    tcApp.config(function($httpProvider){
        var interceptor = ['$rootScope', '$q', function(scope, $q){
                function success(response){
                    return response;
                }

                function error(response){
                    var status = response.status;
                    if ( status == 401 ) {
                        var deferred = $q.defer();
                        window.location = "/login";
                    }
                    // otherwise
                    return $q.reject(response);

                }

                return function(promise){
                    return promise.then(success, error);
                }
            }];
        $httpProvider.responseInterceptors.push(interceptor);
    });

})(angular, tcApp);