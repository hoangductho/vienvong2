/**
 * Created by hoanggia on 4/4/15.
 */

'use strict';

angular
    .module('cropImage')
    .directive('cropLoader', function ($window) {

        var helper = {
            support: !!($window.FileReader && $window.CanvasRenderingContext2D),
            isFile: function (item) {
                return angular.isObject(item) && item instanceof $window.File;
            },
            isImage: function (file) {
                var type = '|' + file.type.slice(file.type.lastIndexOf('/') + 1) + '|';
                return '|jpg|png|jpeg|bmp|gif|'.indexOf(type) !== -1;
            }
        };

        return {
            restrict: 'A',
            scope: {},
            link: function (scope, el, attrs) {
                if (!helper.support) return;

                el.bind('change', function (event) {
                    var files = event.target.files;
                    var file = files[0];

                    if (!helper.isFile(file)) return;
                    if (!helper.isImage(file)) return;

                    var reader = new FileReader();
                    reader.onload = function (loadEvent) {
                        if (!scope.$$phase) {
                            scope.file = loadEvent.target.result;
                            scope.$parent.options.image = scope.file;
                            scope.$parent.options.viewShowCropTool = true;
                        }
                        if (scope.$root.$$phase != '$apply' && scope.$root.$$phase != '$digest') {
                            scope.$apply();
                        }
                    };
                    reader.readAsDataURL(file);
                });
            }
        };
    });
