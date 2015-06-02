/**
 * Created by hoanggia on 4/4/15.
 */

'use strict';

angular
    .module('cropImage')
    .directive('cropImage', function () {
        return {
            restrict: 'A',
            templateUrl: 'modules/cropImage/views/cropShow.html',
            controller: 'cropCtrl',
            scope: {
                options: '='
            }
        };
    });
