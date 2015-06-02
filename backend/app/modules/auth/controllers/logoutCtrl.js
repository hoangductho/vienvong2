/**
 * Created by hoanggia on 5/7/15.
 */

'use strict';

angular
    .module('auth')
    .controller('logoutCtrl', function($rootScope, $state, localStorageService, authConnect){

        var url = $rootScope.apiHost + '/auth/logout';

        authConnect(url).submit({},{auth: $rootScope.online.code}, function(data) {
            localStorageService.remove('online');

            $rootScope.online = localStorageService.get('online');

            $state.go('main.articles.home');
        });
    });