/**
 * Created by hoanggia on 4/12/15.
 */
angular
    .module('facebook')
    .directive('fbLoginButton', function () {
        return {
            restrict: 'A',
            scope: {},
            controller: 'facebookCtrl',
            templateUrl: 'modules/facebook/views/loginButton.html'
        }
    });