/**
 * Created by hoanggia on 6/9/15.
 */
'use strict';

angular
    .module('admin', [
        'ui.router'
    ])
    .config(function ($stateProvider) {
        var modulePath = 'modules/admin/';

        $stateProvider
            .state('main.admin', {
                abstract: true,
                template: '<ui-view></ui-view>',
                controller: 'adminCtrl'
            })
            .state('main.admin.group', {
                url: '/admin/group',
                templateUrl: modulePath + 'views/group.html',
                controller: 'groupCtrl'
            })
            .state('main.admin.role', {
                url: '/admin/role',
                templateUrl: modulePath + 'views/role.html',
                controller: 'roleCtrl'
            });
    });