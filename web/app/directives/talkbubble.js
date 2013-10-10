(function(ng, tcApp){

    tcApp.directive('tcTalkbubble',
        function(){

            return {
                restrict : 'A',
                replace : true,
                scope : {
                    tcTalkbubble : "=",
                    tcTalkbubbleAvatar : "=",
                    tcTalkbubbleDate : "="
                },
                templateUrl : '/app/views/directives/talkbubble.html',
                link : function(scope, element, attrs){
                }

            }

        });
})(angular, tcApp);