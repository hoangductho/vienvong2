/**
 * Created by hoanggia on 3/24/15.
 */

'use strict';

angular
    .module('articles')
    .controller('expressCtrl', function ($rootScope, $scope, $state, $window, $timeout, expressArticles, commentConnect, articleConnect) {
        $scope.detail = {};
        $scope.CommentContent = null;
        $scope.addMessage = null;
        $scope.addStatus = false;
        $scope.listComment = [];
        $scope.suggest = null;
        $scope.listCount = 0;
        $scope.pageComment = 1;
        $scope.linkShare = null;
        var tryAgain = true;

        // get articles from server
        var getDetail = function() {
            expressArticles.get({pid: $state.params.id}, function(data){
                if (data.ok && data.result.length > 0) {
                    $scope.detail = angular.copy(data.result[0]);
                    $window.document.title = $scope.detail.title;
                    $scope.linkShare = '/express/'+ $scope.detail._id;
                    suggest($scope.detail.keyword);
                } else {
                    if(tryAgain){
                        tryAgain = false;
                        var delay = 1;
                        $timeout(function () {
                            getDetail();
                            delay = 0;
                        }, 500, delay > 0);
                    }
                }

            });
        };

        var suggest = function(keyword) {
            var url = $rootScope.apiHost + '/articles/suggest/:pid/:text';
            articleConnect(url).get({pid: $state.params.id ,text: keyword}, function(data){
                if(data.ok && data.result.length > 0) {
                    $scope.suggest = data.result;
                }
            });
        };

        getDetail();

        $scope.resetComment = function() {
            $scope.CommentContent = null;
        };

        $scope.clearMessage = function() {
            var delay = 1;
            $timeout(function(){
                $scope.addMessage = null;
                delay = 0;
            }, 5000, delay>0);
        };

        $scope.commentStatus = function () {

            if(!$rootScope.online) {
                $rootScope.loginBoxShow = true;
                console.log($rootscope.loginBoxShow);
                $scope.addStatus = false;
            }else {
                $scope.addStatus = true;
            }
        };

        $scope.addComment = function() {
            var commentUrl = $rootScope.apiHost + '/comments/add/:pid';
            commentConnect(commentUrl).submit({pid: $state.params.id},{auth: $rootScope.online.code, data: $scope.CommentContent},function(data){
                if(data['ok'] && data['err'] == null) {
                    if($scope.listCount < 10) {
                        $scope.listComment = $scope.listComment.concat(data.data);
                    };
                    $scope.addMessage = 'You added comment successful.';
                    $scope.addStatus = false;
                    $scope.resetComment();
                    $scope.clearMessage();
                }
            });
        };

        $scope.getComment = function(page) {
            if(page > 0) {
                var commentUrl = $rootScope.apiHost + '/comments/index/:pid/:page';
                commentConnect(commentUrl).get({pid: $state.params.id, page: page},function(data){
                    if(data.ok && data.result.length) {
                        $scope.listComment = $scope.listComment.concat(data.result);
                        $scope.listCount = data.result.length;
                        if($scope.listCount == 10) {
                            $scope.pageComment += 1;
                        }else {
                            $scope.pageComment = 0;
                        }
                    }else {
                        $scope.listCount = 0;
                    }
                });
            }else {
                return false;
            }
        };

        $scope.getComment($scope.pageComment);
    });