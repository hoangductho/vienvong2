/**
 * Created by hoanggia on 7/1/15.
 */

'use strict';

angular
    .module('admin')
    .controller('seoCtrl', function ($rootScope, $scope, $sce, $timeout, adminConnect) {
        $scope.links = 0;

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
    });