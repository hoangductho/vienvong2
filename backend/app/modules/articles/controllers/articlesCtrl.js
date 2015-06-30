/**
 * Created by hoanggia on 3/22/15.
 */

'use strict';

angular
    .module('articles')
    .controller('articlesCtrl', function ($scope, $sce, $rootScope, $state, $location, $timeout, articleConnect) {
        // public params
        $scope.state = $state;
        $scope.next = true;
        $scope.listArticles = [];
        $scope.imgHost = 'http://api.vienvong.vn/';
        $scope.searchString = null;
        $scope.searchMess = null;
        var tryAgain = 3;

        // pirate params
        var group = 'all';

        var page = 1;

        var limit = 10;

        // get articles from server
        var list = function (addGroup, addPage) {
            var url = $rootScope.apiHost + '/admin/articles/all/:page';
            if($scope.next) {
                $scope.searchMess = $sce.trustAsHtml('<div class="valid search-status"><i class="fa fa-spinner fa-spin"></i> Searching...</div>');
                articleConnect(url).submit({page: addPage}, {auth: $rootScope.online.code}, function (data) {
                    if (data.ok && data.result.length > 0) {
                        $scope.searchMess = null;
                        $scope.next = true;
                        if (data.result.length < limit) {
                            $scope.next = false;
                        }
                        $scope.listArticles = $scope.listArticles.concat(data.result);
                        page += 1;
                    } else {
                        if(tryAgain > 0) {
                            tryAgain -= 1;
                            var delay = 1;
                            $timeout(function () {
                                list(addGroup, addPage);
                                delay = 0;
                            }, 1000, delay > 0);
                        }else {
                            $scope.next = false;
                            $scope.searchMess = $sce.trustAsHtml('<div class="invalid search-status"><i class="fa fa-times"></i> No result!</div>');
                        }
                    }

                });
            }
        };

        // first get articles
        list(group, page);

        // load more articles
        $scope.loadMore = function() {
            list(group, page)
        };

        $scope.sethot = function(index){
            var url = $rootScope.apiHost + '/admin/articles/sethot/:pid/:value';
            var value = $scope.listArticles[index].hot;
            articleConnect(url).submit({pid: $scope.listArticles[index]._id, value: value}, {auth: $rootScope.online.code}, function (data) {
                if(data.ok && data.err == null) {
                    $scope.listArticles[index].hot = (value>=1?0:1);
                }
            });
        };

        $scope.publish = function(index){
            var url = $rootScope.apiHost + '/admin/articles/publish/:pid/:value';
            var value = ($scope.listArticles[index].others >= 1? 0: 2);
            articleConnect(url).submit({pid: $scope.listArticles[index]._id, value: value}, {auth: $rootScope.online.code}, function (data) {
                if(data.ok && data.err == null) {
                    $scope.listArticles[index].others = value;
                }
            });
        };
    });