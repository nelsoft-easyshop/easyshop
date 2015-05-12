app.directive('whenScrolled', ['$timeout', function($timeout) {
    return function(scope, elm, attr) {
        var raw = elm[0];

        elm.bind('scroll', function() {
            if (raw.scrollTop <= 100) {
                var sh = raw.scrollHeight
                var loadItem = scope.$apply(attr.whenScrolled);

                if (loadItem) {
                    loadItem.then(function() {
                        $timeout(function() {
                            raw.scrollTop = raw.scrollHeight - sh;
                        })
                    });
                }
            }
        });
    };
}]);
