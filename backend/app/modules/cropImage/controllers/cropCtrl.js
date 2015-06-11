/**
 * Created by hoanggia on 4/4/15.
 */

angular
    .module('cropImage')
    .controller('cropCtrl', function ($scope, $rootScope, $http) {
        //the image to output
        $scope.imageOut = '';

        // $broadcast functions

        // cropImage : crop the image and data fill the imageOut with the image.
        // reload the view with the crop image if watermark is set then watermark tool shows.

        $scope.cropImage = function () {
            $scope.$broadcast('cropImage');
        };

        $scope.delImage = function () {
            $scope.options.image = null;
        };

        // cropImageSave : send the image to the window.open() to save the image
        $scope.saveImage = function () {
            $scope.$broadcast('cropImageSave');
        };

        // cropImageShow : after crop output the image with the watermark to the imageOut

        $scope.cropImageShow = function () {
            $scope.$broadcast('cropImageShow');
        };

        // upload image from server
        $scope.uploadImage = function() {
            $http.post($scope.options.uploadUrl, {auth: $rootScope.online.code, source: $scope.options.image})
                .success(function(data, status, headers, config) {
                    $scope.options.resultUpload.photo = data.photo;
                    $scope.options.resultUpload.thumb = data.thumb;
                    $scope.options.image = null;
                })
                .error(function(data, status, headers, config) {
                    console.log(data);
                    console.log(status);
                    console.log(headers);
                    console.log(config);
                });
        };

    });