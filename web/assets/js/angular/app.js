var $baseUrl = "/assets/js/angular";
var app = angular
    .module('messageApp',['ui.router', 'infinite-scroll', 'checklist-model', 'ui.bootstrap'])
    .config(['$stateProvider', '$urlRouterProvider',function($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.otherwise('/');

        // register routes provider
        $stateProvider
            .state('index',{
                url: '/',
                views: {
                    'conversationDetail': {
                        templateUrl: $baseUrl+ '/views/pages/messages/default.html',
                    },
                    'conversationHead': {
                        templateUrl: $baseUrl+ '/views/partials/messagePartners.html',
                        controller: 'MessageController',
                    }
                }
            })
            .state('readMessage',{
                url: '/read/:userId',
                views: {
                    'conversationDetail': {
                        templateUrl: $baseUrl+ '/views/partials/message.html',
                        controller: 'MessageController'
                    },
                    'conversationHead': {
                        templateUrl: $baseUrl+ '/views/partials/messagePartners.html',
                        controller: 'MessageController',
                    }
                }
            });
    }])
    .run(['$state', function ($state) {
        $state.go('index');
    }]);

