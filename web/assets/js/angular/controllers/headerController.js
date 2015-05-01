app.controller('HeaderController', ['$scope', 'HeaderFactory', 
    function($scope, HeaderFactory) {

        $scope.$watch(
            function(){
                return HeaderFactory.getTitle();
            },
            function(newValue, oldValue){
                if(typeof newValue !== 'undefined'){
                    $scope.pageTitle = newValue; 
                }               
            }
        )
                     
        
    }
]);
