app.service('ModalService', ['$modal',
    function ($modal) {

        var modalDefaults = {
            backdrop: true,
            keyboard: true,
            modalFade: true,
            templateUrl: '/app/partials/modal.html'
        };

        var modalOptions = {
            closeButtonText: 'Close',
            actionButtonText: 'OK',
            headerText: 'Proceed?',
            bodyText: 'Perform this action?'
        };

        this.showModal = function (customModalDefaults, customModalOptions) {
            if (!customModalDefaults) {
                customModalDefaults = {};
                customModalDefaults.backdrop = 'static';
            }
            return this.show(customModalDefaults, customModalOptions);
        };

        this.show = function (customModalDefaults, customModalOptions) {
            //Create temp objects to work with since we're in a singleton service
            var tempModalDefaults = {};
            var tempModalOptions = {};

            //Map angular-ui modal custom defaults to modal defaults defined in service
            angular.extend(tempModalDefaults, modalDefaults, customModalDefaults);

            //Map modal.html $scope custom properties to defaults defined in service
            angular.extend(tempModalOptions, modalOptions, customModalOptions);

            tempModalDefaultsCtrl.$inject = ['$scope', '$modalInstance'];
            function tempModalDefaultsCtrl($scope, $modalInstance) {
                $scope.modalOptions = tempModalOptions;
                $scope.modalOptions.ok = function (result) {
                    $modalInstance.close(result);
                };
                $scope.modalOptions.close = function (result) {
                    $modalInstance.dismiss('cancel');
                };
                $scope.modalOptions.callback = function (param1, param2, param3) {
                    var parameterCollection = {
                        param1: param1,
                        param2: param2,
                        param3: param3,
                    } 
                    $modalInstance.close(parameterCollection);
                };
            };

            if (!tempModalDefaults.controller) {
                tempModalDefaults.controller = tempModalDefaultsCtrl;
            }

            return $modal.open(tempModalDefaults).result;
        };
}]);
