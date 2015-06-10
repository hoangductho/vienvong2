/**
 * Created by hoanggia on 3/18/15.
 */
/**
 * @ngdoc overview
 * @name Articles
 * @description
 * # Articles module
 *
 * Module search and show articles
 */

'use strict';

angular
    .module('articles', [
        'ui.router',
        'ngResource',
        'viewhead',
        'cropImage'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'modules/articles/';

        $stateProvider
            .state('main.articles', {
                abstract: true,
                template: '<ui-view></ui-view>',
                controller: function($rootScope, $state){
                    if(!$rootScope.online) {
                        $state.go('main.auth.login');
                    }
                }
            })
            .state('main.articles.home', {
                url: '/',
                templateUrl: modulePath + 'views/listArticles.html',
                controller: 'articlesCtrl'
            })
            .state('main.articles.express', {
                url: '/express/:id?:friendly',
                controller: 'expressCtrl',
                templateUrl: modulePath + 'views/express.html'

            })
            .state('main.articles.search', {
                url: '/search/:text',
                templateUrl: modulePath + 'views/listArticles.html',
                controller: 'searchCtrl'
            })
            .state('main.articles.create', {
                url: '/articles/create',
                templateUrl: modulePath + 'views/create.html',
                controller: 'createCtrl'
            })
            .state('main.articles.edit', {
                url: '/articles/edit/:id',
                templateUrl: modulePath + 'views/edit.html',
                controller: 'editCtrl'
            });
    });