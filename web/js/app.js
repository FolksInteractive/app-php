'use strict';

var tcApp = angular.module( 'tcApp', ['tcApp.directives'] );
// Declare app level module which depends on filters, and services
tcApp.config( ['$routeProvider', '$locationProvider', function( $routeProvider, $locationProvider ) {

        $locationProvider.html5Mode( true );

        $routeProvider.
        when( '/', {
            templateUrl : "/tpl/app-workspace.html",
            controller : "WorkspaceCtrl"
        } )
        .when( '/p/:p/:d', {
            templateUrl : "/tpl/app-project.html",
            controller : "ProjectCtrl",
            //reloadOnSearch:false,

            resolve : tcApp.ProjectCtrlResolver
        } )
        .when( '/p/:p', {
            templateUrl : "/tpl/app-project.html",
            controller : "ProjectCtrl",
            //reloadOnSearch:false,

            resolve : tcApp.ProjectCtrlResolver
        } )
    }] );

// Check if the response is a 401 error to redirect to login
tcApp.config( function( $routeProvider, $locationProvider, $httpProvider ) {
    var interceptor = ['$rootScope', '$q', function( scope, $q ) {
            function success( response ) {
                return response;
            }

            function error( response ) {
                var status = response.status;
                if ( status == 401 ) {
                    var deferred = $q.defer();
                    window.location = "/login";
                }
                // otherwise
                return $q.reject( response );

            }

            return function( promise ) {
                return promise.then( success, error );
            }
        }];
    $httpProvider.responseInterceptors.push( interceptor );
} );