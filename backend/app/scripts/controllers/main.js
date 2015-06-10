'use strict';

/**
 * @ngdoc function
 * @name installApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the installApp
 */
angular.module('vienvong')
    .controller('MainCtrl', function ($scope, $state, $rootScope, $http, $filter, localStorageService, initConnect) {
        // setup api hostname
        $rootScope.apiHost = 'http://api.vienvong.vn';

        //localStorageService.remove('publicKey');
        $rootScope.publicKey = localStorageService.get('publicKey');
        var date = $filter('date')(new Date(), 'yyyy:MM:dd', 'UTC');

        if(!$rootScope.publicKey || $rootScope.publicKey.date < date) {
            var url = $rootScope.apiHost + '/auth/publicKey';
            initConnect(url).init({}, function(data){
                var hash = CryptoJS.SHA256(data.publicHex);
                if(hash = data.publicHash) {
                    localStorageService.set('publicKey', data);
                    $rootScope.publicKey = localStorageService.get('publicKey');
                }
            });
        };

        $rootScope.online = localStorageService.get('online');

        if(!$rootScope.online && !$state.includes('main.auth')) {
            $state.go('main.auth.login');
        }

        // state of ui-router
        $scope.state = $state;

        /* Control facebook show and hide*/
        $scope.facebookShow = false;


        $scope.fbHide = function () {
            $scope.facebookShow = false;
            return 0;
        };

        $scope.fbShow = function () {
            $scope.facebookShow = !($scope.facebookShow);
            return 0;
        };

        /* Control login box show and hide*/
        $scope.loginBoxShow = false;


        $scope.loginHide = function () {
            $scope.loginBoxShow = false;
            return 0;
        };

        $scope.loginShow = function () {
            $scope.loginBoxShow = !($scope.loginBoxShow);
            return 0;
        };

        /* Control login box show and hide*/
        $scope.searchBoxShow = false;


        $scope.searchHide = function () {
            $scope.searchBoxShow = false;
            return 0;
        };

        $scope.searchShow = function () {
            $scope.searchBoxShow = !($scope.searchBoxShow);
            return 0;
        };

    });
