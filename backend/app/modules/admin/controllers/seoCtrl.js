/**
 * Created by hoanggia on 7/1/15.
 */

'use strict';

angular
    .module('admin')
    .controller('seoCtrl', function ($rootScope, $scope, $sce, $timeout, adminConnect) {
        $scope.links = 0;
        $scope.visitor = 0;
        $scope.pageview = 0;

        $scope.sitemap = function() {
            var url = $rootScope.apiHost + '/admin/seo/sitemap';
            adminConnect(url).submit(
                {},
                {auth: $rootScope.online.code, data: $scope.data},
                function(data){
                    if(data.ok && data.links) {
                        $scope.links = data.links;
                    }
                }
            );
        };

        var visitor = function() {
            var url = $rootScope.apiHost + '/admin/seo/statistic/visitor/1';

            adminConnect(url).submit(
                {},
                {auth: $rootScope.online.code, data: $scope.data},
                function(data){
                    console.log(data);
                    if(data.ok && data.result.length) {
                        angular.forEach(data.result, function(value){
                            $scope.visitor = data.result.length;
                        });
                    }
                }
            );
        };

        var pageview = function() {
            var url = $rootScope.apiHost + '/admin/seo/statistic/uri/1';

            adminConnect(url).submit(
                {},
                {auth: $rootScope.online.code, data: $scope.data},
                function(data){
                    console.log(data);
                    if(data.ok && data.result.length) {
                        angular.forEach(data.result, function(value){
                            $scope.pageview += value.count;
                        });
                    }
                }
            );
        };

        visitor();

        pageview();

    });