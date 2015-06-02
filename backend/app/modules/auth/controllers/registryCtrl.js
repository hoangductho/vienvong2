/**
 * Created by hoanggia on 4/17/15.
 */

'use strict';

angular
    .module('auth')
    .controller('registryCtrl', function ($scope, $sce, $rootScope, $state, $timeout, authConnect) {

        var auth = {
            email: null,
            fullname: null,
            password: null,
            retype: null
        };

        var validate = {
            email: {
                regexp: "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$",
                mess: ''
            },
            fullname: {
                regexp: '^.{6,64}$',
                mess: ''
            },
            password: {
                regexp: '^[\\S]{8,256}$',
                mess: ''
            },
            retype: {
                mess: ''
            }
        };

        var insertedInit = {
            email: 0,
            password: 0,
            retype: 0
        };

        var inserted = angular.copy(insertedInit);

        $scope.validate = angular.copy(validate);
        $scope.auth = angular.copy(auth);
        $scope.result = {
            ok: 0,
            err: null
        };
        $scope.scores = 0;
        $scope.delay = 5;

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

        $scope.retype = function() {
            if($scope.auth.retype == $scope.auth.password) {
                $scope.validate.retype.mess = $sce.trustAsHtml('<div class="valid form-control"><i class="fa fa-check-circle"></i> valid</div>');

                if(!inserted.retype) {
                    $scope.scores += 1;
                    inserted.retype = 1;
                }
            }else {
                $scope.validate.retype.mess = $sce.trustAsHtml('<div class="invalid form-control"><i class="fa fa-times-circle"></i> invalid</div>');

                if(inserted.retype) {
                    $scope.scores -= 1;
                    inserted.retype = 0;
                }
            }
        };

        $scope.reset = function () {
            $scope.validate = angular.copy(validate);
            $scope.auth = angular.copy(auth);
            inserted = angular.copy(insertedInit);
            $scope.scores = 0;
        };

        $scope.process = function() {
            if($scope.scores != 4) {
                return false;
            }else {
                var url = $rootScope.apiHost + '/auth/registry';
                var rsakey = new RSAKey();

                rsakey.setPublic($rootScope.publicKey.publicHex, "10001");

                var encrypt = rsakey.encrypt($scope.auth.email + '/' + $scope.auth.password + '/' + $scope.auth.fullname);

                authConnect(url).submit({},{auth: encrypt}, function(data) {
                    if(data.done && data.message == 'success') {
                        $scope.result.ok = 1;
                        $timeout(function(){
                            $state.go('main.auth.login');
                        }, 5000);
                        countTime(5);
                    }else {
                        $scope.result.err = data.message;
                    }
                });
            }
        };

        function countTime(delay) {
            $scope.delay = delay;
            $timeout(function(){
                delay -= 1;
                countTime(delay);
            }, 1000, delay>=0);
        }
    });