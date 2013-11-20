(function(ng, module){
    module.service("thread.Socket",
        function($rootScope, $http, $timeout, thread, thread_sync_path){
            var TIMER_DELAY = 8000;

            // Flag the pause state of the synchroniser
            var pause = false;

            // Cache the last comment id
            var lastTimestamp = getLastTimestamp(thread.comments);

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
                    var newTimestamp = response.lastTimestamp;

                    // Check if the server returned a different timestamp
                    if ( newTimestamp > lastTimestamp ) {
                        
                        fetch();
                        lastTimestamp = newTimestamp;
                    } else {
                        $timeout(sync, TIMER_DELAY);
                    }

                }).error(function(error){
                    console.log("Error occur while synching with server");
                });
            }

            /*
             * Fetch the thread from the server and update the 
             * thread's thread to keep the model updated
             */
            function fetch(){
                $http({
                    method : 'GET',
                    url : thread_sync_path + "?pull"
                }).success(function(response){
                    angular.extend(thread, response.thread);
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
            function getLastTimestamp(commentList){
                if ( commentList.length > 0 && commentList[commentList.length - 1].id )
                    return commentList[commentList.length - 1].createdAt;

                return  -1;
            }

            // We watch on the comment list to keep the last comment id updated 
            // when the current user add a new comment
            $rootScope.$watch(
                function(){
                    return thread.comments
                },
                function(newComments, oldComments){
                    if ( !newComments )
                        return;

                    sync();
                    // Update the last comment id with the newly added comment
                    //lastTimestamp = getLastTimestamp(newComments) || lastTimestamp;

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
        }
    );
})(angular, module);