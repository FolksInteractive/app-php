'use strict';
/* Services */


//var models = angular.module('tcApp.models', []);    
/** ----------------------------------------- **/
/** --------------- WORKSPACE --------------- **/
tcApp.factory( 'WorkspaceManager', function( WorkspaceDAO, ProjectDAO ) {
    return {
        createProject : function( workspace, project ) {
// $http returns a promise, which has a then function, which also returns a promise
            return ProjectDAO.post( project )
                    .success( function( project ) {
// The then function here is an opportunity to modify the response
                workspace.vendor_projects.unshift( project );
                // The return value gets picked up by the then in the controller.
                return project;
            } )
                    .error( function( response ) {
                return response;
            } )
        },
        findProject : function( workspace, idProject ) {
            var project = findVendorProject( workspace, idProject );
            if ( project == { } ) {
                project = findVendorProject( workspace, idProject );
            }
            if ( project == { } ) {
                project = { };
            }

            return project;
        },
        findVendorProject : function( workspace, idProject ) {
            var project = { };
            angular.forEach( workspace.vendor_projects, function( p ) {
                if ( p.id == idProject ) {
                    project = p;
                }
            } );
            return project;
        },
        findClientProject : function( workspace, idProject ) {
            var project = { };
            angular.forEach( workspace.client_projects, function( p ) {
                if ( p.id == idProject ) {
                    project = p;
                }
            } );
            return project;
        }
    }
} );
tcApp.factory( 'WorkspaceDAO', function( $http ) {
    var url = '//timecrumbs/app_dev.php/api/workspace';
    return {
        get : function( ) {
            return  $http( {
                method : "GET",
                url : url
            } );
        }
    }
} )
/** ----------------------------------------- **/
/** -------------    PROJECT    ------------- **/
tcApp.factory( 'ProjectManager', function( ProjectDAO, DemandDAO ) {
    var self = {
        createDemand : function( project, demand ) {
            // $http returns a promise, which has a then function, which also returns a promise
            return DemandDAO.post( demand, project.id )
                    .success( function( demand ) {
                // The then function here is an opportunity to modify the response
                project.demands.unshift( demand );
                // The return value gets picked up by the then in the controller.
                return demand;
            } )
                    .error( function( response ) {
                return response;
            } )
        },
        updateDemand : function( project, demand, values ) {
            angular.extend(demand, values);
            // $http returns a promise, which has a then function, which also returns a promise
            return DemandDAO.put( values, demand.id, project.id )
                    .success( function( demand ) {
                // The then function here is an opportunity to modify the response
                // self.findDemand(project, demand.id) = demand;
                // The return value gets picked up by the then in the controller.
                return demand;
            } )
                    .error( function( response ) {
                return response;
            } )
        },
        getDemand : function( project, idDemand ) {
            return DemandDAO.get( idDemand, project.id )
                    .success( function( demand ) {
                return demand;
            } )
                    .error( function( response ) {
                return response;
            } )
        },
        findDemand : function( project, idDemand ) {
            var demand = { };
            angular.forEach( project.demands, function( d ) {
                if ( d.id == idDemand ) {
                    demand = d;
                }
            } );
            return demand;
        },
        enrollClient : function( project, enrollment ) {
            return ProjectDAO.enroll( project.id, enrollment )
                    .success( function( ) {
                project.is_client_enrolld = true;
                project.client_enrollment = { "email" : enrollment.email };
                return enrollment;
            } )
                    .error( function( response ) {
                return response;
            } )
        }
    }
    return self
} );
tcApp.factory( 'ProjectDAO', function( $http ) {
    var url = '//timecrumbs/app_dev.php/api/projects';
    return {
        get : function( id ) {
            return  $http( {
                method : "GET",
                url : url + '/' + id
            } );
        },
        post : function( project ) {
            return $http( {
                method : "POST",
                url : url,
                data : {
                    project : project
                }
            } );
        },
        enroll : function( id, enrollment ) {
            return $http( {
                method : "POST",
                url : url + "/" + id + "/enroll",
                data : {
                    enrollment : enrollment
                }
            } );
        }
    }
} )

/** ------------------------------------------ **/
/** -------------     DEMAND     ------------- **/
tcApp.factory( 'ThreadManager', function( DemandDAO ) {
    var self = {
        addComment : function( comment, demand ) {
            demand.thread.comments.push(comment);
            return DemandDAO.postComment( comment, demand.id, demand.project.id )
            .success( function(c) {
                angular.extend(comment, c);
                return comment;
            } )
            .error( function( response ) {
                return response;
            } );
        }
    }
    return self;
} )

tcApp.factory( 'DemandDAO', function( $http ) {
    var url = '//timecrumbs/app_dev.php/api/projects';
    return{
        get : function( id, idProject ) {
            return  $http( {
                method : "GET",
                url : url + '/' + idProject + '/demands/' + id
            } );
        },
        post : function( demand, idProject ) {
            return $http( {
                method : "POST",
                url : url + '/' + idProject + '/demands',
                data : {
                    demand : demand
                }
            } );
        },
        postComment : function( comment, idDemand, idProject ) {
            return $http( {
                method : "POST",
                url : url + '/' + idProject + '/demands/' + idDemand + "/comment",
                data : {
                    comment : comment
                }
            } );
        },
        put : function( values, idDemand, idProject ) {
            return $http( {
                method : "PUT",
                url : url + '/' + idProject + '/demands/' + idDemand,
                data : {
                    demand : values
                }
            } );
        }
    }
} );
tcApp.factory( 'DemandList', function( ) {
    var originalDemands = [];
    var filteredDemands = [];
    var demandFilters = {
        approved : false,
        completed : false
    };
    var filter = function( ) {
        filteredDemands = angular.copy( originalDemands );
        for ( var i = 0; i < filteredDemands.length; i++ )
        {
            var d = filteredDemands[i];
            if ( d.approved != demandFilters.approved || d.completed != demandFilters.completed ) {
                filteredDemands.splice( i, 1 );
            }
        }
//console.log(filteredDemands);
    }

    var self = {
        getDemands : function( ) {
            return filteredDemands
        },
        setDemands : function( list ) {
            originalDemands = list;
            filteredDemands = originalDemands
            //filter();
        },
        filterByDemand : function( demand ) {
            demandFilters.completed = demand.completed;
            demandFilters.approved = demand.approved;
            filter( );
        },
        filterByName : function( name ) {
            switch ( name ) {
                case "unapproved":
                    demandFilters.completed = false;
                    demandFilters.approved = false;
                    break;
                case "approved":
                    demandFilters.completed = false;
                    demandFilters.approved = true;
                    break;
                case "completed":
                    demandFilters.completed = true;
                    demandFilters.approved = true;
                    break;
            }
            filter( );
        },
        getCurrentFilterName : function( ) {
            if ( !demandFilters.approved && !demandFilters.completed ) {
                return "unapproved";
            }
            if ( demandFilters.approved && !demandFilters.completed ) {
                return "approved";
            }
            if ( demandFilters.approved && demandFilters.completed ) {
                return "completed";
            }
        }
    }

    return self;
} )