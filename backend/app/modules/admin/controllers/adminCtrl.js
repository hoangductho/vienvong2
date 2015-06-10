/**
 * Created by hoanggia on 6/10/15.
 */
'use strict';

angular
    .module('admin')
    .controller('adminCtrl', function ($rootScope, $state) {
        if(!$rootScope.online) {
            $state.go('main.auth.login');
        }
    });