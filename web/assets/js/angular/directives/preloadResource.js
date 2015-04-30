
/**
 * Preload Resource Data into AngularJS
 *
 * https://robots.thoughtbot.com/preload-resource-data-into-angularjs
 * Example: <div ng-cloak preload-resource="foo"></div>
 * you can now access value foo by using $scope.preloadResource in your controller
 */
app.directive("preloadResource", function() {
    return {
        link: function(scope, element, attrs) {
            scope.preloadResource = JSON.parse(attrs.preloadResource);
            element.remove();
        }
    };
});
