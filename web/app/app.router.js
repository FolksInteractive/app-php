(function(ng, tcApp){

    var routes = [];
    routes["workspace.dashboard"] = "/";
    routes["workspace.pricebook"] = "/pricebook";
    routes["workspace.pricebook.item"] = "/pricebook/:idItem";
    routes["workspace.contacts"] = "/contacts";
    routes["workspace.contacts.contact"] = "/contacts/:idContact";
    routes["workspace.project.orders"] = "/p/:idProject";
    routes["workspace.project.agreement"] = "/p/:idProject/agreement";
    routes["workspace.project.progress"] = "/p/:idProject/progress";
    routes["workspace.project.bill"] = "/p/:idProject/bill";
    routes["workspace.project.invoices"] = "/p/:idProject/invoices";
    routes["workspace.project.settings"] = "/p/:idProject/settings";
    routes["workspace.project.orders.order"] = "/p/:idProject/:idOrder";

    // Define routes as a application constant
    tcApp.constant("routes", routes);

// ----------------------------------------------------------------------- //
// ----------------------------------------------------------------------- //

    // Setup $routeProvider with the routes constants
    tcApp.config(function(routes, $routeProvider){
        for ( var routeName in routes )
        {
            var route = routes[routeName];

            $routeProvider.when(
                route,
                {
                    action : routeName
                }
            );
        }
    });

// ----------------------------------------------------------------------- //
// ----------------------------------------------------------------------- //

    // This service helps working with the routes of the App.
    tcApp.service(
        "Router",
        function(routes){

            // Returns a formatted path for a specific route name
            function getPath(routeName, params){

                // Default the route name.
                routeName = (routeName || "");

                // Default the params. 
                params = (params || { });

                // Default the retourned path
                var path = (routes[routeName] || "");
                if ( path == "" )
                    throw new Error("Route " + routeName + " could not be found")

                // Replace param name by the param value in the specified path
                for ( var key in params )
                {
                    path = path.replace(":" + key, params[key]);
                }

                return path;
            }


            // ---------------------------------------------- //
            // ---------------------------------------------- //

            return {
                getPath : getPath
            };

        });
})(angular, tcApp)