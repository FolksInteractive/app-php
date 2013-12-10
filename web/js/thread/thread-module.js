var module = angular.module("Thread", ['tc.filters.timeago']);

module.controller("thread.Controller", [
    "$scope", "$http", "thread", "avatars", "utils.DateService", "thread.Socket", "thread_comment_path", "workspace",
    function($scope, $http, thread, avatars, DateService, Socket, thread_comment_path, workspace){

        function groupComments(comments){
            
            var output = [];
            var currentGroup;
            var previousComment = null;
            
            for ( var i = 0; i < comments.length; i++ ) {
                var comment = comments[i];

                //If the author is not the same as the previous comment
                if ( i === 0 || comment.author.id !== previousComment.author.id ) {
                    currentGroup = { };
                    currentGroup.author = comment.author;
                    currentGroup.comments = [];
                    output.push(currentGroup);
                }

                currentGroup.comments.push(comment);
                currentGroup.created_at = new Date(comment.created_at+" UTC");

                previousComment = comment;
            }
            return output;
        }

        $scope.getAvatar = function(author){
            return avatars['wp_' + author.id];
        }

        $scope.submitComment = function(){
            var comment = $scope.newComment;
            comment.createdAt = DateService.now();
            comment.author = workspace;
            thread.comments.push(comment);

            // Reset newComment instance
            $scope.newComment = { };

            // Prepare data objet to send to server
            var data = { };
            data.body = comment.body;
            data.createdAt = DateService.now();
            // Submitting form to server
            $http({
                method : 'POST',
                url : thread_comment_path,
                data : data,
                headers : {
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

        // Bring the thread model to the scope
        $scope.thread = thread;

        $scope.groupedComments = groupComments(thread.comments);

        $scope.$watch('thread.comments', function(newValue, oldValue){
            $scope.groupedComments = groupComments(thread.comments);
        }, true);
        
        // Starts the synchroniser
        //Socket.start();
    }
]);