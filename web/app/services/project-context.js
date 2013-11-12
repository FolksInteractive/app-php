(function(ng, tcApp){
    tcApp.service("ProjectContext", 
    
    function($rootScope, $location, requestContext, Router, WorkspaceManager, ProjectManager){

        // I initialize data based on the request params
        function loadData(){
            // Store project ID
            var idProject = requestContext.getParam("idProject");
            // Store order ID
            var idOrder = requestContext.getParam("idOrder");

            // If idProject is specified
            if ( idProject != null && (project == null || idProject != project.id) ) {

                // Reset data
                order = null;
                roleInProject = null;
                
                // Temporary short version of the selected project
                project = WorkspaceManager.findProject($rootScope.workspace, idProject);

                // Load project data
                loadProject(idProject);
                
            }else if( idProject == null ){
                project = null;
                roleInProject = null;
                order = null;
            }

            // If idOrder is specified
            if ( idOrder != null && (order == null || idOrder != order.id) ) {

                // If current project, reset order with a temporary short version of the selected order
                // This way, it can highlight the selected order in the list
                order = (project != null) ? ProjectManager.findOrder(project, idOrder) : null;

                //Load order data
                loadOrder(idProject, idOrder)
                
                    .success(function(response){  
                    });
            }else{
                order = null;
            }
        }

        // I load project data
        function loadProject(idProject){
            // Project loading process
            projectLoading = true;

            return WorkspaceManager.getProject($rootScope.workspace, idProject)
                
                .success(function(response){

                    // store loaded project to context
                    project = response;
                    
                    roleInProject = findRoleInProject();
                    
                    // Update project loading status
                    projectLoading = false;

                    return project;
                })

                .error(function(response){
                    // Reset project object
                    project = null;

                    // Update project loading status
                    projectLoading = false;

                    // If no project found redirect to project list
                    $location.path(
                        Router.getPath("workspace.dashboard")
                    );
                });
        }

        // I load order data
        function loadOrder(idProject, idOrder){
            orderLoading = true;
            return ProjectManager.getOrder({ id : idProject }, idOrder)
                .success(function(response){

                // Store loaded order to context
                order = response;
                orderLoading = false;

                return order;
            })
                .error(function(response){

                // Reset order object
                order = null;

                // Update order loading status
                orderLoading = false;

                // If no order found redirect to order list
                $location.path(
                    Router.getPath("workspace.project.orders", {"idProject":idProject})
                );
            });
        }
        
        function getProject(){
            return project;
        }
        
        function getProjectOrders(){
            return project.orders;
        }

        function isProjectLoading(){
            return projectLoading;
        }

        function getOrder(){
            return order;
        }

        function isOrderLoading(){
            return orderLoading;
        }
        
        function getOrderSection(_order){
            
            if ( !_order.approved && !_order.completed ) {
                return "active";
            }
            if ( _order.approved && !_order.completed ) {
                return "progress";
            }
            if ( _order.approved && _order.completed ) {
                return "bill";
            }
            
            return "active";
        }
        
        function findRoleInProject(){
            var id = $rootScope.workspace.id;
            // If project is ready
            if( project && project.id ){
                
                // Checks if the user is the vendor of the project
                if( project.vendor && project.vendor.id == id ){
                    return ROLE_VENDOR;
                }
                
                // Checks if the user is the vendor of the project
                if( project.client && project.client.id == id ){
                    return ROLE_CLIENT;
                }
                
                // Checks if the user is the vendor of the project
                if( project.collaborators ){
                    // Loop in collaborators list
                    for( var i = 0; i < project.collaborators; i++){
                        if( project.collaborator[i].id == id ){
                            return ROLE_COLLABORATOR;
                        }
                    }
                }
                
            }
            
        }
        
        function getRoleInProject(){
            return roleInProject;
        }
        
        function isVendorInProject(){
            return roleInProject == ROLE_VENDOR;
        }
        
        function isClientInProject(){
            return roleInProject == ROLE_CLIENT;
        }
        
        function isCollaboratorInProject(){
            return roleInProject == ROLE_COLLABORATOR;
        }
        // ---------------------------------------------- //
        // ---------------------------------------------- //

        // CONSTANT
        var ROLE_VENDOR = "vendor";
        var ROLE_CLIENT = "client";
        var ROLE_COLLABORATOR = "collaborator";
                
        // Store the current project
        var project = { };

        // Store project loading status
        var projectLoading = false;

        // Store the current order, can be null
        var order = { };

        // Store order loading status
        var orderLoading = false;
        
        // Store the user's role in the project
        var roleInProject = "";
        
        // ---------------------------------------------- //
        // ---------------------------------------------- //

        $rootScope.$on("requestContextChanged", loadData);
        
        
        loadData();

        // ---------------------------------------------- //
        // ---------------------------------------------- //


        // Return the public API.
        return({
            ROLE_VENDOR : ROLE_VENDOR,
            ROLE_CLIENT : ROLE_CLIENT,
            ROLE_COLLABORATOR : ROLE_COLLABORATOR,
            getProject : getProject,
            isProjectLoading : isProjectLoading,
            getOrder : getOrder,
            isOrderLoading : isOrderLoading,
            getRoleInProject : getRoleInProject,
            isVendorInProject : isVendorInProject,
            isClientInProject : isClientInProject,
            isCollaboratorInProject : isCollaboratorInProject
        });


    });
})(angular, tcApp)