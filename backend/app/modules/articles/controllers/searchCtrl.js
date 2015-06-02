/**
 * Created by hoanggia on 5/30/15.
 */

'use strict';

angular
    .module('articles')
    .controller('searchCtrl', function ($scope, $rootScope, $state, $location, getArticles, hotArticles, articleConnect) {
        // public params
        $scope.state = $state;
        $scope.next = true;
        $scope.listArticles = [];
        $scope.imgHost = 'http://api.vienvong.vn/';
        $scope.searchString = null;

        var page = 1;

        var limit = 10;

        // get articles from server
        var  list = function() {
            if($scope.next) {
                var text = $state.params.text;
                var url = $rootScope.apiHost + '/articles/search/:text/:page';
                articleConnect(url).get({text: text, page: page}, function(data){
                    if(data.ok && data.result.length > 0) {
                        if(data.result.length < limit) {
                            $scope.next = false;
                        }
                        $scope.listArticles = $scope.listArticles.concat(data.result);
                        page += 1;
                    }
                });
            }
        };

        // load more articles
        $scope.loadMore = function() {
            console.log('Searching');
            list();
        };

        list();

        $scope.searchArticle = function() {
            var text = encodeURIComponent($scope.searchString);
            $state.go('main.articles.search', {'text': text});
        }
    });