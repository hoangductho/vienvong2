/**
 * Created by hoanggia on 4/14/15.
 */

'use strict';

angular
    .module('auth', [
        'ui.router'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'modules/auth/';

        $stateProvider
            .state('main.auth', {
                abstract: true,
                templateUrl: modulePath + 'views/auth.html'
            })
            .state('main.auth.registry', {
                url: '/auth/registry',
                templateUrl: modulePath + 'views/registry.html',
                controller: 'registryCtrl'
            })
            .state('main.auth.login', {
                url: '/auth/login',
                templateUrl: modulePath + 'views/login.html',
                controller: 'loginCtrl'
            })
            .state('main.auth.forgot', {
                url: '/auth/forgot',
                templateUrl: modulePath + 'views/forgot.html',
                controller: 'forgotCtrl'
            })
            .state('main.auth.logout', {
                url: '/auth/logout',
                //templateUrl: modulePath + 'views/forgot.html',
                controller: 'logoutCtrl'
            });
    });