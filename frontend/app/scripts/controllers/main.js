'use strict';

/**
 * @ngdoc function
 * @name installApp.controller:MainCtrl
 * @description
 * # MainCtrl
 * Controller of the installApp
 */
angular.module('vienvong')
    .controller('MainCtrl', function ($scope, $state, $rootScope, $http, $filter, $location,
                                      $anchorScroll, $window, localStorageService, initConnect) {
        // setup api hostname
        $rootScope.apiHost = 'http://api.vienvong.vn';

        $rootScope.online = localStorageService.get('online');

        $rootScope.meta = {
            title: null,
            description: null,
            image: null
        };

        // state of ui-router
        $scope.state = $state;

        $rootScope.$on('$stateChangeStart', function(){
            $location.hash('top');
            $anchorScroll();
            $location.hash(null);
        });

        if($window.innerWidth < 991){
            $rootScope.rightEdgeShow = false;
        }else {
            $rootScope.rightEdgeShow = true;
        }

        /* Control facebook show and hide*/
        $scope.facebookShow = false;
        $scope.fanpageInit = false;


        $scope.fbHide = function () {
            $scope.facebookShow = false;
            return 0;
        };

        $rootScope.fbShow = function () {
            $scope.facebookShow = !($scope.facebookShow);
            if($scope.fanpageInit == false) {
                $scope.fanpageInit = true;
            }

            return 0;
        };

        /* Control login box show and hide*/
        $rootScope.loginBoxShow = false;


        $scope.loginHide = function () {
            $rootScope.loginBoxShow = false;
            return 0;
        };

        $scope.loginShow = function () {
            $rootScope.loginBoxShow = !($rootScope.loginBoxShow);
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
