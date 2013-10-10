(function( ng, tcApp ){

	"use strict";

	tcApp.controller(
		"workspace.project.orders.ThreadController",
		function( $scope, ThreadManager ) {


			// --- Define Controller Methods. ------------------- //


			// --- Define Scope Methods. ------------------------ //
            
            $scope.openThread = function(){
                $scope.showThread = true;
            }
            
            $scope.addComment = function() {
                
                var newCommentCopy = ng.copy($scope.newComment);
                $scope.newComment = {};
                
                return ThreadManager.addComment(newCommentCopy, $scope.order)
                    
                    .success( function( comment ) {
                        return comment;
                    })
                    
                    .error( function( response ) {
                        return response;
                    });
                    
            }
            
			// --- Define Controller Variables. ----------------- //
                        

			// --- Define Scope Variables. ---------------------- //
            $scope.showThread = false;

            $scope.newComment = {};
            
			// --- Bind To Scope Events. ------------------------ //


			// --- Initialize. ---------------------------------- //



		}
	);

})( angular, tcApp );