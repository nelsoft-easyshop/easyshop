app.directive('csrfDirective', ['$http', function($http) {
    return {
        restrict: 'A',
        link: function(scope, elem, attr) {
            $http.defaults.headers.common['X-CSRF-TOKEN'] = attr.csrfDirective;
        }
    }
}]);
