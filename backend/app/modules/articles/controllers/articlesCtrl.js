/**
 * Created by hoanggia on 3/22/15.
 */

'use strict';

angular
    .module('articles')
    .controller('articlesCtrl', function ($scope, $rootScope, $state, $location, getArticles, hotArticles, articleConnect) {
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

        // get articles from server
        var list = function(addGroup, addPage) {
            getArticles.get({group: addGroup, page: addPage}, function(data){
                if(data.result.length < limit) {
                    $scope.next = false;
                }
                $scope.listArticles = $scope.listArticles.concat(data.result);
                page += 1;
            });
        };

        // get hot articles
        var hot = function() {
            hotArticles.get(function(data) {
                $scope.hotArticles = data.result;
            })
        };

        // set group name
        if($state.params.group && $state.params.group !== 'all') {
            group = $state.params.group;
        }else {
            hot();
        }

        // first get articles
        list(group, page);

        // load more articles
        $scope.loadMore = function() {
            list(group, page)
        };
    });