/**
 * Created by hoanggia on 4/15/15.
 */

'use strict';

angular
    .module('auth')
    .controller('loginCtrl', function ($scope, $sce, $rootScope, $state, $filter,localStorageService, authConnect, $http) {
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

        var auth = {
            email: null,
            password: null
        };

        var validate = {
            email: {
                regexp: "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$",
                mess: '',
                valid: true
            },
            password: {
                regexp: '^[\\S]{8,256}$',
                mess: '',
                valid: true
            }
        };

        var insertedInit = {
            email: 0,
            password: 0
        };

        var inserted = angular.copy(insertedInit);

        $scope.validate = angular.copy(validate);
        $scope.auth = angular.copy(auth);
        $scope.result = {
            ok: 0,
            err: null
        };
        $scope.scores = 0;
        $scope.keepMe = false;

        $scope.keepOnline = function() {
            if($scope.keepMe){
                localStorageService.set('keepMe', true)
            }else {
                localStorageService.remove('keepMe')
            }
        };

        $scope.valid = function(feild) {
            var patten = new RegExp($scope.validate[feild].regexp);
            var res = patten.test($scope.auth[feild]);

            if(res) {
                $scope.validate[feild].mess = $sce.trustAsHtml('<div class="valid form-control"><i class="fa fa-check-circle"></i> ' +feild+ ' valid</div>');
                $scope.validate[feild].valid = true;
                if(!inserted[feild]) {
                    $scope.scores += 1;
                    inserted[feild] = 1;
                }

            }else {
                $scope.validate[feild].mess = $sce.trustAsHtml('<div class="invalid form-control"><i class="fa fa-times-circle"></i> ' +feild+ ' invalid</div>');
                $scope.validate[feild].valid = false;
                if(inserted[feild]) {
                    $scope.scores -= 1;
                    inserted[feild] = 0;
                }
            }

        };

        $scope.reset = function () {
            $scope.validate = angular.copy(validate);
            $scope.auth = angular.copy(auth);
            inserted = angular.copy(insertedInit);
            $scope.scores = 0;
        };

        // get articles from server
        $scope.process = function() {

            // get IP location of client
            $http.get('http://freegeoip.net/json/').success(function(data, status, headers, config) {
                $scope.auth.ip = data.ip;
            });

            // authenticate hash string
            var passHash = CryptoJS.SHA256($scope.auth.password);
            var auth_hmac = CryptoJS.algo.HMAC.create(CryptoJS.algo.SHA256, passHash.toString());
            auth_hmac.update($scope.auth.email);
            var authentication = auth_hmac.finalize();

            var url = $rootScope.apiHost + '/auth/login';
            var rsakey = new RSAKey();

            rsakey.setPublic($rootScope.publicKey.publicHex, "10001");

            var encrypt = rsakey.encrypt($scope.auth.email + '/' + authentication + '/' + $scope.auth.ip);

            authConnect(url).submit({},{auth: encrypt}, function(data) {
                if(data.ok) {
                    $scope.result.ok = 1;

                    var online = data;
                    online.email = $scope.auth.email;

                    localStorageService.set('online', data);

                    $rootScope.online = localStorageService.get('online');

                    $scope.reset();

                    if($state.is('main.auth.login')) {
                        $state.go('main.articles.home')
                    }
                }else {
                    $scope.result.err = 'your email or password is incorrect.';
                }
            });
        };
    });