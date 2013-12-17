angular.module('tc.directives.numbers', [])

    .directive('tcNumbersOnly',
    function(){
        return {
            restrict : "A",
            require : 'ngModel',
            link : function(scope, element, attrs, modelCtrl){

                // We have to add the parser at the beginning of the list 
                // to format the string before it is validated by 
                // ng-ng-required or pattern or other
                modelCtrl.$parsers.unshift(function(value){

                    if ( ng.isUndefined(value) )
                        return '';

                    // Remove any caracter not a number
                    var newValue = value.replace(/[^\d]/g, '');

                    if ( newValue !== value ) {
                        modelCtrl.$setViewValue(newValue);
                        modelCtrl.$render();
                    }

                    return newValue;
                });
            }
        }
    })

    // Pretty much the same things as the numbers only but accept doubles 00.00
    .directive('tcDoublesOnly',
    function(){
        return {
            restrict : "A",
            require : 'ngModel',
            link : function(scope, element, attrs, modelCtrl){

                // We have to add the parser at the beginning of the list 
                // to format the string before it is validated by 
                // ng-ng-required or pattern or other
                modelCtrl.$parsers.unshift(function(value){

                    if ( ng.isUndefined(value) )
                        return '';

                    // Remove any caracter not a number or a dot
                    var newValue = value.replace(/[^\d|\.]/g, '');
                    // Keep only the good part of the strings
                    newValue = newValue.replace(/^(\d*\.?\d{0,2})(.*)*/, '$1');

                    if ( newValue !== value ) {
                        modelCtrl.$setViewValue(newValue);
                        modelCtrl.$render();
                    }

                    return newValue;
                });
            }
        }
    });