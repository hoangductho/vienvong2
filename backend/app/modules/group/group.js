/**
 * Created by hoanggia on 6/9/15.
 */
'use strict';

angular
    .module('group', [
        'ui.router'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'modules/group/';

        $stateProvider
            .state('main.group', {
                abstract: true,
                template: '<ui-view></ui-view>',
                controller: function($rootScope, $state){
                    if(!$rootScope.online) {
                        $state.go('main.auth.login');
                    }
                }
            })
            .state('main.group.actions', {
                url: '/group',
                templateUrl: modulePath + 'views/group.html',
                controller: 'groupCtrl'
            });
    });