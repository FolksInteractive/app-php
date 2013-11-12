( function( ng, tcApp ) {
    
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
                    
            getProject : function( workspace, idProject ) {
                // $http returns a promise, which has a then function, which also returns a promise
                return ProjectDAO.get( idProject )
                        .success( function( project ) {
                            // The return value gets picked up by the then in the controller.
                            return project;
                        })
                        
                        .error( function( response ) {
                            return response;
                        })
            },
                    
            findProject : function( workspace, idProject ) {
                var project = this.findVendorProject( workspace, idProject );
                
                // If project not found in the vendor project list 
                // check in the client project list
                if ( !project.id ) {
                    project = this.findClientProject( workspace, idProject );
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
} )( angular, tcApp )