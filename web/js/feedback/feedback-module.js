var feedbackModule = angular.module("Feedback", []);

feedbackModule.controller("feedback.Controller", function($scope, $http){
    $scope.openFeedbackModal = function(){
        $scope.showFeedbackModal = true;
    }

    $scope.closeFeedbackModal = function(){
        $scope.showFeedbackModal = false;
    }

    $scope.sendFeedback = function(){
        // Reset newFeedback instance
        $scope.newFeedback = { };

        // Prepare data objet to send to server
        var data = { };
        data[$scope.form_config.body_name] = $scope.newFeedback.body;
        data[$scope.form_config.uri_name] = $scope.form_config.uri_value;
        data[$scope.form_config.token_name] = $scope.form_config.token_value;

        // Submitting form to server
        $http({
            method : 'POST',
            url : $scope.form_config.form_action,
            data : $.param(data),
            headers : {
                'Content-Type' : 'application/x-www-form-urlencoded',
                'X-Requested-With' : 'XMLHttpRequest'
            }

        }).success(function(c){
            $scope.closeFeedbackModal();
        }).error(function(error){
        });
    }
    
    $scope.showFeedbackModal = false;
    $scope.newFeedback = {};
});

feedbackModule.directive("tcFeedbackModal",
    function(){
        return{
            restrict : "A",
            link : function(scope, element, attrs){
                $("body").append(element);
                //Watching in the OrderController
                scope.$watch('showFeedbackModal', function(val){
                    if ( val ) {
                        $('#feedbackModal').modal('show');
                    } else {
                        $('#feedbackModal').modal('hide');
                    }
                });

                $('#feedbackModal').on('hidden.bs.modal', function(){
                    scope.$apply(function(){
                        scope.closeFeedbackModal();
                    });
                });
                
                $('#feedbackModal').on('shown.bs.modal', function(){
                    scope.$apply(function(){
                        scope.openFeedbackModal();
                    });
                });
            }
        }
    }
);
