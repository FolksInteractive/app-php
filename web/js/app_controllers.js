'use strict';


tcApp.controller( "AppCtrl", function( $scope, $location, ProjectDAO ) {

    $scope.selectProject = function( idProject ) {
        $location.path( "/p/" + idProject );
    }
} );

tcApp.controller( "WorkspaceCtrl", function( $scope, WorkspaceManager ) {
    $scope.newProject = { };
    $scope.createProject = function() {

        return WorkspaceManager.createProject( $scope.workspace, $scope.newProject )
                .success( function( project ) {
            $scope.newProject = { };
            return project;
        } )
                .error( function( response ) {
            return response;
        } );
    };

    $scope.updateProject = function() {

    }
} );

tcApp.controller( "ProjectCtrl", function( $scope, $location, project, demand, ProjectManager, DemandList ) {
    $scope.loadingDemand = false;
    $scope.project = project;
    $scope.enrollment = { };
    $scope.newDemand = { };
    $scope.demand = ( demand && demand.id ) ? ProjectManager.findDemand( $scope.project, demand.id ) : { };

    DemandList.setDemands( $scope.project.demands );
    //DemandList.filterByDemand( $scope.demand );
    
    $scope.demands = DemandList.getDemands();
    $scope.$watch(DemandList.getDemands, function(value){
        $scope.demands = value
        $scope.demand = {};
    });
    
    $scope.enrollClient = function() {
        return ProjectManager.enrollClient( $scope.project, $scope.enrollment )
                .success( function( enrollment ) {
            return enrollment;
        } )
                .error( function( response ) {
            return response;
        } );
    }

    $scope.selectDemand = function( demand ) {
        $location.path( "/p/" + $scope.project.id + "/" + demand.id )
        $scope.demand = {};
        $scope.loadingDemand = true;

        ProjectManager.getDemand( $scope.project, $scope.demand.id )
                .success( function( d ) {
            angular.copy( d, $scope.demand );
            $scope.loadingDemand = false;
        } )
                .error( function( response ) {
            console.log( response )

            $scope.loadingDemand = false;
        } );
    }

    $scope.submitDemand = function() {
        ProjectManager.createDemand( $scope.project, $scope.newDemand )
                .success( function( demand ) {
            $scope.newDemand = { };
            console.log( demand )
        } )
                .error( function( response ) {
            console.log( response )
        } );
    }

} );

tcApp.ProjectCtrlResolver = {
    project : function( $q, $route, $location, ProjectDAO ) {
        var idProject = ( $route.current.params.p );
        var deferred = $q.defer();

        ProjectDAO.get( idProject )
                .success( function( response ) {
            deferred.resolve( response );
        } )
                .error( function( response ) {
            $location.path( "/" );
        } );

        return deferred.promise;
    },
    demand : function( $q, $route, DemandDAO ) {
        var idProject = ( $route.current.params.p );
        var idDemand = ( $route.current.params.d );

        if ( !idDemand )
            return;

        var deferred = $q.defer();

        DemandDAO.get( idDemand, idProject )
        .success( function( response ) {
            deferred.resolve( response );
        } )
        .error( function( response ) {
            deferred.resolve( null );
        } );

        return deferred.promise;
    }
}


tcApp.controller( "EditDemandCtrl", function( $scope, ProjectManager, ThreadManager ) {
    $scope.details = "";
    $scope.value = "";  
    $scope.$watch('demand', function(demand){
        console.log(demand);
        $scope.details = demand.details;     
        $scope.value = demand.value;
    });
   
    $scope.updateDetails = function() {
        ProjectManager.updateDemand( $scope.project, $scope.demand, {
            "details" : $scope.details
        } );
    }
    
    $scope.updateValue = function(){
        ProjectManager.updateDemand( $scope.project, $scope.demand, {
            "value" : $scope.value
        } );
    }    
    
    $scope.newComment = {};
    $scope.addComment = function() {
        ThreadManager.addComment($scope.newComment, $scope.demand)
                .success( function( comment ) {
        })
                .error( function( response ) {
        });
        $scope.newComment = {};
        
    }

    $scope.showDetails = function() {
        return ( $scope.demand.id && !$scope.loadingDemand )
    }
    $scope.showLoading = function() {
        return ( $scope.loadingDemand )
    }
    $scope.showNothing = function() {
        return ( !$scope.demand.id && !$scope.loadingDemand )
    }
} );