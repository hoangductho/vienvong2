/**
 * Created by hoanggia on 6/10/15.
 */
'use strict';

angular
    .module('articles')
    .controller('articlesFirstCtrl', function ($rootScope, $state) {
        if(!$rootScope.online) {
            $state.go('main.auth.login');
        }
    });