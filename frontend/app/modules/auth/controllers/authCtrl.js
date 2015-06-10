/**
 * Created by hoanggia on 6/10/15.
 */

'use strict';

angular
    .module('auth')
    .controller('logoutCtrl', function($rootScope, $filter, localStorageService){

        $rootScope.publicKey = localStorageService.get('publicKey');
        var date = $filter('date')(new Date(), 'yyyy:MM:dd', 'UTC');

        if(!$rootScope.publicKey || $rootScope.publicKey.date < date) {
            var url = $rootScope.apiHost + '/auth/publicKey';
            initConnect(url).init({}, function(data){
                var hash = CryptoJS.SHA256(data.publicHex);
                if(hash = data.publicHash) {
                    localStorageService.set('publicKey', data);
                    $rootScope.publicKey = localStorageService.get('publicKey');
                }
            });
        };
    });