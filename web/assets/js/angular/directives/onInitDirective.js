app.directive('onInitDirective', function() {
    return {
        scope: { someCtrlFn: '&callbackFn' },
        link: function(scope, element, attrs) {
            scope.someCtrlFn({arg1: 0});
        },
    }
});
