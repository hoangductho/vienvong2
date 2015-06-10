/**
 * Created by hoanggia on 3/25/15.
 */
'use strict';

angular
    .module('articles')
    .controller('editCtrl', function ($rootScope, $scope, $state, detailArticles, updateArticles) {
        $scope.detail = {};

        // get articles from server
        var getDetail = function() {
            detailArticles.data({pid: $state.params.id}, {auth: $rootScope.online.code}, function(data){
                if(data.ok && data.result[0]) {
                    $scope.detail = data.result[0];
                }else {
                    $state.go('main.articles.home');
                }
            });
        };

        getDetail();

        $scope.editArticles = function () {
            updateArticles.update({pid: $state.params.id}, {data: $scope.detail, auth: $rootScope.online.code}, function(data) {
                if(data.ok == 1 && data.n ==1 && data.err == null) {
                    $state.go('main.articles.express', {id: $state.params.id});
                }
            })
        }
    });