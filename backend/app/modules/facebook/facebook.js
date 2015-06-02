/**
 * Created by hoanggia on 4/8/15.
 */
angular
    .module('facebook', [])
    /*.run(function($rootScope, $http){
        function statusChangeCallback(response) {
            // The response object is returned with a status field that lets the
            // app know the current login status of the person.
            // Full docs on the response object can be found in the documentation
            // for FB.getLoginStatus().
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
        }

        window.fbAsyncInit = function() {

            // Now that we've initialized the JavaScript SDK, we call
            // FB.getLoginStatus().  This function gets the state of the
            // person visiting this page and can return one of three states to
            // the callback you provide.  They can be:
            //
            // 1. Logged into your app ('connected')
            // 2. Logged into Facebook, but not your app ('not_authorized')
            // 3. Not logged into Facebook and can't tell if they are logged into
            //    your app or not.
            //
            // These three cases are handled in the callback function.

            FB.getLoginStatus(function(response) {
                statusChangeCallback(response);
            });

        };

    })*/;