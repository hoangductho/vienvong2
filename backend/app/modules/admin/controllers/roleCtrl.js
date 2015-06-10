/**
 * Created by hoanggia on 6/9/15.
 */

'use strict';

angular
    .module('admin')
    .controller('roleCtrl', function ($rootScope, $scope, $sce, $timeout, adminConnect) {
        var roleStore = null;

        $scope.data = {
            'email': null,
            'group': null,
            'permission': 0
        };

        $scope.point = -1;

        $scope.permission = [
            {value: 1, alias: '1:Read (R)'},
            {value: 2, alias: '2:Write (W)'},
            {value: 3, alias: '3:R+W'},
            {value: 4, alias: '4:Execute (E)'},
            {value: 5, alias: '5:R+E'},
            {value: 6, alias: '6:W+E'},
            {value: 7, alias: '7:R+W+E'}
        ];

        $scope.addMessage = null;
        $scope.searchString = null;
        $scope.searchMess = null;
        $scope.listRoles = null;
        $scope.next = true;
        var page = 1;
        var limit = 10;
        var tryAgain = true;

        var clearMessage = function() {
            var delay = 1;
            $timeout(function(){
                $scope.addMessage = null;
                delay = 0;
            }, 3000, delay>0);
        };

        $scope.addRole = function() {
            var url = $rootScope.apiHost + '/admin/role/add';
            adminConnect(url).submit(
                {},
                {auth: $rootScope.online.code, data: $scope.data},
                function(data){
                    if(data.ok && data.err == null) {
                        $scope.addMessage = $sce.trustAsHtml('<br><div class="valid form-control"><i class="fa fa-check-circle"></i> Add group successful</div>');
                    }else {
                        $scope.addMessage = $sce.trustAsHtml('<br><div class="valid form-control"><i class="fa fa-check-circle"></i> '+ data.err +'</div>');
                    }

                    clearMessage();
                }
            );
        };

        $scope.getRole = function() {
            $scope.searchMess = $sce.trustAsHtml('<br><br><div class="valid search-status form-control normal-post none-radius"><i class="fa fa-spinner fa-spin"></i> Searching...</div>');

            var url = $rootScope.apiHost + '/admin/role/list/:page/:text';
            var text = angular.copy($scope.searchString);
            if(!text) {
                text = 0;
            }
            adminConnect(url).submit({page: page, text: text},{auth: $rootScope.online.code},function(data){
                if(data.ok) {
                    roleStore = data.result;
                    $scope.listRoles = angular.copy(roleStore);
                    $scope.searchMess = null;
                    if(!data.result.length) {
                        $scope.searchMess = $sce.trustAsHtml('<div class="invalid search-status form-control normal-post none-radius">No More Result</div>');
                    }

                    if(data.result.length < limit) {
                        $scope.next = false;
                    }else {
                        page += 1;
                    }
                }else {
                    if(tryAgain) {
                        $scope.getRole();
                        tryAgain = false;
                    }
                }
            });
        };

        $scope.getRole();

        $scope.changePer = function(permission, index) {
            $scope.point = index;
            $scope.listRoles = angular.copy(roleStore);
            $scope.listRoles[index].permission = permission;
        };

        $scope.reset = function() {
            $scope.point = -1;
            $scope.listRoles = angular.copy(roleStore);
        };
    });