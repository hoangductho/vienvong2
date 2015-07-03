'use strict';

/**
 * @ngdoc overview
 * @name installApp
 * @description
 * # installApp
 *
 * Main module of the application.
 */
angular
    .module('vienvong', [
        'ngResource',
        'ui.router',
        'offClick',
        'LocalStorageModule',

        //'seo',
        'markdown',
        'facebook',

        'articles',
        'auth'
    ])
    .config(function ($stateProvider, $urlRouterProvider, $locationProvider, localStorageServiceProvider, $httpProvider) {
        $httpProvider.defaults.withCredentials = true;
        $stateProvider
            .state('main', {
                abstract: true,
                template: '<ui-view></ui-view>'
                //templateUrl: 'views/main.html',
                //controller: 'MainCtrl'
            })
            .state('main.info', {
                abstract: true,
                template: '<ui-view></ui-view>'
            })
            .state('main.info.sitemap', {
                url: '/info/sitemap',
                templateUrl: 'views/sitemap.html'
            })
            .state('main.info.about', {
                url: '/info/about',
                templateUrl: 'views/about.html'
            })
            .state('main.info.copyright', {
                url: '/info/copyright',
                templateUrl: 'views/copyright.html'
            })
            .state('main.info.privacy', {
                url: '/info/privacy',
                templateUrl: 'views/privacy.html'
            });


        $locationProvider.html5Mode(true);
        $locationProvider.hashPrefix('!');
        localStorageServiceProvider.setPrefix('ls');

        $(document).ajaxComplete(function () {
            //console.log('FB re-parse');
            try {
                FB.XFBML.parse();
            } catch (ex) {
            }
        });
    });
