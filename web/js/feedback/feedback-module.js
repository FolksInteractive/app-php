var feedbackModule = angular.module("Feedback", []);

feedbackModule.controller("feedback.Controller", function($scope, $http){
    $scope.openFeedbackModal = function(){
        $scope.showFeedbackModal = true;
    }

    $scope.closeFeedbackModal = function(){
        $scope.showFeedbackModal = false;
    }

    $scope.sendFeedback = function(){

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
            // Reset newFeedback instance
            $scope.newFeedback = { };
            $scope.request.success = true;
            $scope.request.status = "sent";
        }).error(function(error){
            $scope.request.success = false;
            $scope.request.status = "sent";
        });
        
        $scope.request.status = "sending";
    }
    
    $scope.showFeedbackModal = false;
    $scope.newFeedback = {};
    $scope.request = {
        success: false,
        status : ""
    };
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

angular.bootstrap($('#feedbackModal'), ["Feedback"]);