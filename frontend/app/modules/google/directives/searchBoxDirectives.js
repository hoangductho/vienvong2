/**
 * Created by hoanggia on 7/7/15.
 */

angular
    .module('google')
    .directive("googleSearch", function ($window, $compile) {
        var modulePath = 'modules/google/';
        return {
            restrict: 'AC',
            //replace: true,
            scope: {
            },
            templateUrl: modulePath + 'views/searchBox.html'
        }
    });