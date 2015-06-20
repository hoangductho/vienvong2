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
        'ng-optimizely',

        'seo',
        'markdown',
        'facebook',

        'articles',
        'auth'
    ])
    .config(function ($stateProvider, $urlRouterProvider, $locationProvider, localStorageServiceProvider) {

        $stateProvider
            .state('main', {
                abstract: true,
                templateUrl: 'views/main.html',
                controller: 'MainCtrl'
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
    })
    .run(['optimizely', function(optimizely) {
        optimizely.loadProject('880950754');
    }]);;
