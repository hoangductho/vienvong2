/**
 * Created by hoanggia on 6/9/15.
 */

'use strict';

angular
    .module('group')
    .controller('groupCtrl', function ($rootScope, $scope, $sce, $timeout, groupConnect) {
        $scope.data = {
            'name': null
        };

        $scope.addMessage = null;
        $scope.searchString = null;
        $scope.searchMess = null;
        $scope.listGroups = null;
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

        $scope.addGroup = function() {
            var url = $rootScope.apiHost + '/admin/group/add';
            groupConnect(url).submit(
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

        $scope.getGroup = function() {
            $scope.searchMess = $sce.trustAsHtml('<br><br><div class="valid search-status form-control normal-post none-radius"><i class="fa fa-spinner fa-spin"></i> Searching...</div>');

            var url = $rootScope.apiHost + '/admin/group/list/:page/:text';
            var text = angular.copy($scope.searchString);
            if(!text) {
                text = 0;
            }
            groupConnect(url).submit({page: page, text: text},{auth: $rootScope.online.code},function(data){
                console.log(data);
                if(data.ok) {
                    $scope.listGroups = angular.copy(data.result);
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
                        $scope.getGroup();
                        tryAgain = false;
                    }
                }
            });
        };

        $scope.getGroup();
    });