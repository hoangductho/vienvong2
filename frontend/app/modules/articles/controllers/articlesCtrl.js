/**
 * Created by hoanggia on 3/22/15.
 */

'use strict';

angular
    .module('articles')
    .controller('articlesCtrl', function ($scope, $rootScope, $state, $location, $timeout, getArticles, hotArticles, articleConnect) {
        // public params
        $scope.state = $state;
        $scope.next = true;
        $scope.listArticles = [];
        $scope.hotArticles = false;
        $scope.imgHost = 'http://api.vienvong.vn/';
        $scope.searchString = null;

        // pirate params
        var group = 'all';

        var page = 1;

        var limit = 10;

        // get hot articles
        var hot = function() {
            hotArticles.get(function(data) {
                if (data.ok && data.result.length > 0) {
                    $scope.hotArticles = data.result;
                } else {
                    var delay = 1;
                    $timeout(function () {
                        hot();
                        delay = 0;
                    }, 1000, delay > 0);
                }
                ;
            })
        };

        // set group name
        if($state.params.group && $state.params.group !== 'all') {
            group = $state.params.group;
        }else {
            hot();
        }

        // get articles from server
        var list = function (addGroup, addPage) {
            getArticles.get({group: addGroup, page: addPage}, function (data) {
                if (data.ok && data.result.length > 0) {
                    if (data.result.length < limit) {
                        $scope.next = false;
                    }
                    $scope.listArticles = $scope.listArticles.concat(data.result);
                    page += 1;
                } else {
                    var delay = 1;
                    $timeout(function () {
                        list(addGroup, addPage);
                        delay = 0;
                    }, 1000, delay > 0);
                }

            });
        };

        // first get articles
        list(group, page);

        // load more articles
        $scope.loadMore = function() {
            list(group, page)
        };
    });