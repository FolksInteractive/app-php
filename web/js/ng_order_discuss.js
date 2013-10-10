var module = angular.module("OrderDiscuss", []);

module.controller("OrderDiscussController",
    function($scope, $http, order, user_role, sf_form_config, DateService, Socket){

        $scope.submitComment = function(){
            var comment = $scope.newComment;
            comment.createdAt = DateService.now();
            order.thread.comments.push(comment);

            // Reset newComment instance
            $scope.newComment = { };

            // Prepare data objet to send to server
            var data = { };
            data[sf_form_config.body_name] = comment.body;
            data[sf_form_config.token_name] = sf_form_config.token_value;

            // Submitting form to server
            $http({
                method : 'POST',
                url : sf_form_config.form_action,
                data : $.param(data),
                headers : {
                    'Content-Type' : 'application/x-www-form-urlencoded',
                    'X-Requested-With' : 'XMLHttpRequest'
                }

            }).success(function(c){
                angular.extend(comment, c);
                //console.log("Comment addition completed id:"+c.id);


            }).error(function(error){
                console.log(error)
            });
        }

        // Cache the new comment data
        $scope.newComment = { };

        // Bring the order model to the scope
        $scope.order = order;

        // Brings the user roles to the scope
        $scope.is_client = user_role.is_client;
        $scope.is_vendor = user_role.is_vendor;
        $scope.is_creator = user_role.is_creator;

        // Starts the synchroniser
        //Socket.start();
    }
);

/*
 * This a util service for dates
 */
module.service("DateService", function(){
    /*
     * Return the datetime of the current moment
     */
    var now = function(){
        var date;
        date = new Date();
        date = date.getUTCFullYear() + '-' +
            ('00' + (date.getUTCMonth() + 1)).slice(-2) + '-' +
            ('00' + date.getUTCDate()).slice(-2) + ' ' +
            ('00' + date.getUTCHours()).slice(-2) + ':' +
            ('00' + date.getUTCMinutes()).slice(-2) + ':' +
            ('00' + date.getUTCSeconds()).slice(-2);

        return date;
    }

    /*
     * Public API
     */
    return {
        now : now
    };
})

module.service("Socket", function($rootScope, $http, $timeout, order, thread_sync_path){
    var TIMER_DELAY = 8000;

    // Flag the pause state of the synchroniser
    var pause = false;

    // Cache the last comment id
    var lastCommentId = getLastCommentId(order.thread.comments);

    /*
     * Starts the synchronizer
     */
    function start(){
        pause = false;
        sync();
    }

    /*
     * This is a public method to pause the synchroniser
     */
    function pause(){
        pause = true;
    }

    /*
     * Every 3 seconds this function is call to check on the server 
     * the last comment id of the thread. If it is not the same it calls 
     * the fetch method to update the thread
     */
    function sync(){
        if ( pause )
            return;

        $http({
            method : 'GET',
            url : thread_sync_path
        })
            .success(function(response){
            var newLastCommentId = response.lastCommentId;

            // Check if the server returned a different comment id
            if ( newLastCommentId !== lastCommentId ) {
                //console.log("Fetching : id from server ("+newLastCommentId+") is different from the one here ("+lastCommentId+")");
                fetch();
                lastCommentId = newLastCommentId;
            } else {
                $timeout(sync, TIMER_DELAY);
            }

        }).error(function(error){
            console.log("Error occur while synching with server");
        });
    }

    /*
     * Fetch the thread from the server and update the 
     * thread's order to keep the model updated
     */
    function fetch(){
        $http({
            method : 'GET',
            url : thread_sync_path+"?pull"
        }).success(function(response){
            angular.extend(order.thread, response.thread);
            /*console.log("Fetch callback : ")    
             console.log(response.thread);*/
            $timeout(sync, TIMER_DELAY);
        })
    }

    /*
     * Finds the last item of a list and returns its id
     * @param array commentList
     * @returns integer
     */
    function getLastCommentId(commentList){        
        if( commentList.length > 0 && commentList[commentList.length - 1].id)
            return commentList[commentList.length - 1].id;
        
        return  -1;
    }

    // We watch on the comment list to keep the last comment id updated 
    // when the current user add a new comment
    $rootScope.$watch(
        function(){
            return order.thread.comments
        },
        function(newComments, oldComments){
            if ( !newComments )
                return;

            // Update the last comment id with the newly added comment
            lastCommentId = getLastCommentId(newComments) || lastCommentId;

            //console.log("User action : Last comment id updated to "+lastCommentId);
        },
        true
        );

    start();

    /*
     * Public API
     */
    return{
        start : start,
        pause : pause
    }
});

module.directive("tcThread", function($document, $window, order){
    return{
        restrict : "C",
        link : function(scope, element, attrs){
            scope.$watch(
                function(){
                    return order.thread.comments
                },
                function(newComments, oldComments){
                    if ( !newComments || newComments == oldComments)
                        return;
                    
                    if ( newComments.length == oldComments.length)
                        return;
                    
                    $document.scrollTop($document.height());
                },
                true
            );
        }
    }
})