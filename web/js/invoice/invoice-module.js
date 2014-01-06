var module = angular.module("Invoice", ['ui.bootstrap.datepicker', 'tc.directives.numbers']);

module.controller("invoice.Controller", function($scope, form_name, invoice){    
    
    $scope.form_name = form_name;
    
    function getFormName(prop){
        return form_name+"["+prop+"]";
    };
    
    $scope.getFormName = function(prop){
        return getFormName(prop);
    };
    
    $scope.invoice = invoice;
    
});