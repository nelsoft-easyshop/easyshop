app.directive('scrollBottomOn', ['$timeout', function($timeout) {
    return function(scope, elm, attr) {
        scope.$watch(attr.scrollBottomOn, function(value) {
            if (value) {
                $timeout(function() {
                    scope.$apply(function () {
                        elm[0].scrollTop = elm[0].scrollHeight;
                    });
                });
            }
        });
    }
}]);
