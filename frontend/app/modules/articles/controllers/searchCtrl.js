/**
 * Created by hoanggia on 5/30/15.
 */

'use strict';

angular
    .module('articles')
    .controller('searchCtrl', function ($scope, $sce, $rootScope, $state, $location, $timeout, getArticles, hotArticles, articleConnect) {
        // public params
        $scope.state = $state;
        $scope.next = true;
        $scope.listArticles = [];
        $scope.imgHost = 'http://api.vienvong.vn/';
        $scope.searchString = null;
        var tryAgain = true;
        $scope.searchMess = null;

        var page = 1;

        var limit = 10;

        // get articles from server
        var  list = function() {
            if($scope.next) {
                $scope.searchMess = $sce.trustAsHtml('<div class="valid search-status"><i class="fa fa-spinner fa-spin"></i> Searching...</div>');
                var text = $state.params.text;
                var url = $rootScope.apiHost + '/articles/search/:text/:page';
                articleConnect(url).get({text: text, page: page}, function(data){
                    if(data.ok && data.result.length > 0) {
                        $scope.next = true;
                        $scope.searchMess = null;
                        if(data.result.length < limit) {
                            $scope.next = false;
                        }
                        $scope.listArticles = $scope.listArticles.concat(data.result);
                        page += 1;
                    }else {
                        if(tryAgain){
                            tryAgain = false;
                            var delay = 1;
                            $timeout(function () {
                                list();
                                delay = 0;
                            }, 500, delay > 0);
                        }else {
                            $scope.next = false;
                            $scope.searchMess = $sce.trustAsHtml('<div class="invalid search-status"><i class="fa fa-times"></i> No result!</div>');
                        }
                    }
                });
            }
        };

        list();

        // load more articles
        $scope.loadMore = function() {
            list();
        };

        $scope.searchArticle = function() {
            var text = encodeURIComponent($scope.searchString);
            $state.go('main.articles.search', {'text': text});
        }
    });