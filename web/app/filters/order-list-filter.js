(function(ng, tcApp){

    tcApp.filter('ordersfilter',
        function( ProjectContext ){
            
            // This filter sync the rendered order list with 
            // the current section from the ProjectContext
            
            return function(orders){
                if( !orders )
                    return;
                
                var section = ProjectContext.getSection();
                // Store the expected values
                var completed;
                var approved;

                // Set the expected values corresponding to the current section
                switch ( section ) {
                    case "active":
                        completed = false;
                        approved = false;
                        break;

                    case "progress":
                        completed = false;
                        approved = true;
                        break;

                    case "bill":
                        completed = true;
                        approved = true;
                        break;
                }

                // Clear the order list
                var filteredOrders = [];

                // For each active order, keep those answering criterias
                for ( var i = 0, length = orders.length; i < length; i++ ) {

                    // Store Order instance
                    var d = orders[i];

                    // Check if order answers criterias
                    if ( d.approved == approved && d.completed == completed ) {
                        filteredOrders.push(d);
                    }
                }
                
                return filteredOrders;
            }

        }

    );

})(angular, tcApp);