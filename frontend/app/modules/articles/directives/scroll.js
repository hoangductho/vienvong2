/**
 * Created by hoanggia on 6/20/15.
 */

angular
    .module('articles')
    .directive("scroll", function ($window) {
        return function (scope, element, attrs) {
            angular.element($window).bind("scroll", function () {
                if (this.pageYOffset >= 100) {
                    scope.fanpageInit = true;
                }
                scope.$apply();
            });
        };
    });