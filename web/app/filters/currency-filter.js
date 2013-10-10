(function(ng, tcApp){

    tcApp.filter('currencyfilter',
        
        function( ){
            
            // This filter shows only recentComments
            return function(value){
                // if value is string convert to number
                if(ng.isString(value))
                    value = parseFloat(value) 
                
                // if value is a number
                if(!isNaN(parseFloat(value)) && isFinite(value)) {
                    value = "$ " + value.toFixed(2);
                }else{
                    value =  "N/A";
                }
                
                return "<span class='number'>"+value+"</span>";
            }

        }

    );

})(angular, tcApp);