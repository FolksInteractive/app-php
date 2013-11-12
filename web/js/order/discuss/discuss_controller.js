var module = angular.module("OrderDiscuss", []);

module.controller("order.discuss.Controller",[
    "$scope", "$http", "order", "avatars", "user_role", "sf_form_config", "utils.DateService", "order.discuss.Socket",
    function($scope, $http, order, avatars, user_role, sf_form_config, DateService, Socket){
        
        $scope.getAvatar = function( comment ){                
                return avatars['workspace_'+comment.author.id];
        }
        
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
]);