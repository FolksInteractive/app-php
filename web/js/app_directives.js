

/* Directives */
angular.module( 'tcApp.directives', [] ).
        directive( 'tcProjectSelector', function() {
    return {
        restrict : "C",
        template : '' +
                '<div class="btn-group">' +
                '   <button class="btn">{{project.name}}</button>' +
                '   <button class="btn dropdown-toggle" data-toggle="dropdown">' +
                '       <span class="caret"></span>' +
                '   </button>' +
                '   <ul class="dropdown-menu">' +
                '       <li ng-repeat="p in workspace.vendor_projects"><a ng-click="select(p.id)">{{p.name}}</a></li>' +
                '       <li ng-repeat="p in workspace.client_projects"><a ng-click="select(p.id)">{{p.name}}</a></li>' +
                '   </ul>' +
                '</div>',
        link : function( scope, element, attrs ) {
        }
    }
} )
        .directive( 'tcCreateProject', function() {
    return {
        restrict : 'A',
        templateUrl : '/tpl/directive-create-project.html',
        link : function( scope, element, attrs ) {
            scope.creatingProject = false;

            scope.openModal = function() {
                $( '#tcCreateProjectModal' ).modal( 'show' );
            }

            scope.submitProject = function() {
                scope.creatingProject = true;

                scope.createProject()
                        .success( function( project ) {
                    scope.creatingProject = false;
                    $( '#tcCreateProjectModal' ).modal( 'hide' );
                } )
                        .error( function( response ) {
                    scope.creatingProject = false;
                } );
            }
        }

    }
} )
        .directive( 'tcInviteClient', function() {
    return {
        restrict : 'E',
        templateUrl : '/tpl/directive-enroll-client.html',
        link : function( scope, element, attrs ) {
            scope.sendingEnrollment = false;
            scope.openModal = function() {
                $( '#tcInviteClientModal' ).modal( 'show' );
            }

            scope.submitInvite = function() {
                scope.sendingEnrollment = true;
                scope.enrollClient()
                        .success( function( enrollment ) {
                    scope.sendingEnrollment = false;
                    $( '#tcInviteClientModal' ).modal( 'hide' );
                } )
                        .error( function( response ) {
                    scope.sendingEnrollment = false;
                } );
            }
        }
    }
} )
        .directive( 'tooltip', function() {
    return {
        restrict : 'A',
        link : function( scope, element, attrs )
        {
            $( element )
                    .attr( 'title', scope.$eval( attrs.tooltip ) )
                    .tooltip( { placement : attrs.tooltipPlacement } );
        }
    }
} )
        .directive( 'tcDemandFilter', function( DemandList ) {
    return {
        restrict : 'A',
        
        scope : { },
        link : function( scope, element, attrs ) {
            scope.type = attrs.tcDemandFilter;
            scope.filter = function() {
                DemandList.filterByName( attrs.tcDemandFilter );
            }
            
            scope.$watch(DemandList.getCurrentFilterName, function(value){
                if( value == attrs.tcDemandFilter ){
                    element.addClass("selected");
                }else{
                    element.removeClass("selected");
                }
            })
        }
    }
} )

.directive( 'tcThread', function( DemandList ) {
    return {
        restrict : 'A',
        template : '<div class="tc-comment-older" ng-click="viewOlder()" ng-show="demand.thread.comments.length > nbRecent">View older comments ({{getThread().comments.length - nbRecent}})</div>'+
                   '<div class="tc-comment" ng-repeat="comment in comments">'+
                   '   <div class="tc-avatar">'+
                   '       <img width="32" height="32" class="img-circle">'+
                   '   </div>'+
                   '   <div class="tc-comment-author"></div>'+
                   '   <div class="tc-comment-body">'+
                   '       {{comment.body}}'+
                   '   </div>'+
                   '</div>',           
        link : function( scope, element, attrs ) {
            element.addClass("tc-thread");
            
            scope.nbRecent = 3;
            scope.comments = [];
            
            scope.getThread = function(){
                return scope.demand.thread;
            }
                       
            scope.viewOlder = function(e){
                scope.comments = scope.getThread().comments;
                element.find(".tc-comment-older").hide();
            }
            
            scope.$watch(scope.getThread, function(t){
                if(scope.getThread()){
                    element.find(".tc-comment-older").show();
                    scope.comments = scope.getThread().comments.slice(0,3) || [];
                }
            });
                        
            //console.log(scope.demand);
            //scope.comments = scope.demand.thread.comments.slice(0,3);
            //console.log(scope.demand.thread.comments);
        }
    }
} )