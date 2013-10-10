(function(ng, tcApp){
    tcApp.service("PricebookContext",
        function($rootScope, $location, requestContext, Router, PricebookManager){

            // I initialize data based on the request params
            function loadData(){
                // Store item ID
                var idItem = requestContext.getParam("idItem");

                // If idItem is specified
                if ( idItem != null && (item == null || idItem != item.id) ) {

                    // Temporary short version of the selected item
                    item = PricebookManager.findItem($rootScope.workspace.pricebook, idItem);
                    
                    // Load item data
                    loadItem(idItem);

                } else if ( idItem == null ) {
                    item = null;
                }
            }

            // I load item data
            function loadItem(idItem){
                itemLoading = true;
                return PricebookManager.getItem(idItem)
                    .success(function(i){

                    // Store loaded item to context
                    item = i;
                    itemLoading = false;

                    return item;
                })
                    .error(function(response){

                    // Reset order object
                    item = null;

                    // Update order loading status
                    itemLoading = false;

                    // If no order found redirect to pricebook
                    $location.path(
                        Router.getPath("workspace.pricebook")
                        );
                });
            }


            function getPricebook(){
                return pricebook;
            }

            function getItem(){
                return item;
            }

            function setItem( i ){
                item = i;
                $location.path(
                    Router.getPath("workspace.pricebook.item",{idItem:i.id})
                );
            }
            
            function isItemLoading(){
                return itemLoading;
            }

            // ---------------------------------------------- //
            // ---------------------------------------------- //

            // CONSTANT

            // Store the pricebook
            var pricebook = $rootScope.workspace.pricebook;

            // Store the current item, can be null
            var item = null;

            // Store item loading status
            var itemLoading = false;


            // ---------------------------------------------- //
            // ---------------------------------------------- //

            $rootScope.$on("requestContextChanged", loadData);


            loadData();

            // ---------------------------------------------- //
            // ---------------------------------------------- //


            // Return the public API.
            return({
                getPricebook : getPricebook,
                getItem : getItem,
                setItem : setItem,
                isItemLoading : isItemLoading
            });


        });
})(angular, tcApp)