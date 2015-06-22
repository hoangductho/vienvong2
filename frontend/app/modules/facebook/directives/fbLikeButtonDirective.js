/**
 * Created by hoanggia on 6/22/15.
 */
angular
    .module('facebook')
    .directive('fbLikeButton', function () {
        return {
            restrict: 'C',
            scope: {
                link: '='
            },
            templateUrl: 'modules/facebook/views/fbLikeButton.html'
        }
    });