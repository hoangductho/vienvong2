/**
 * Created by hoanggia on 7/7/15.
 */

'use strict';

angular
    .module('google', [
        'ui.router'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'modules/google/';

        $stateProvider
            .state('main.google', {
                abstract: true,
                template: '<ui-view></ui-view>'
            })
            .state('main.google.search', {
                url: '/google/search',
                templateUrl: modulePath + 'views/search.html'
            });
    });