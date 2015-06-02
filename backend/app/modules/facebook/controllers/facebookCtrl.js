/**
 * Created by hoanggia on 4/8/15.
 */

'use strict';

angular
    .module('facebook')
    .controller('facebookCtrl', function ($scope, $rootScope) {
        $scope.fbLogin = function() {
            FB.login(function(response, $http){
                if (response.status === 'connected') {
                    // Logged into your app and Facebook.
                    // send code to server

                    var url = $rootScope.apiHost + '/auth/facebook';
                    console.log(url);

                    var params = {
                        accessToken: response.authResponse.accessToken,
                        signedRequest: response.authResponse.signedRequest
                    };

                    $http.post(url, params)
                        .success(function(data, status, headers, config){
                            console.log('Server check fb login');
                            console.log(data);
                        })
                        .error(function(data, status, headers, config){
                            console.log('Error connect server');
                            console.log(data);
                            console.log(status);
                            console.log(headers);
                            console.log(config);
                        })
                }
            },{
                //auth_type: 'rerequest'
            });
        }
    });