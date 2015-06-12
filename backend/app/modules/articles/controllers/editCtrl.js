/**
 * Created by hoanggia on 3/25/15.
 */
'use strict';

angular
    .module('articles')
    .controller('editCtrl', function ($rootScope, $scope, $state, detailArticles, updateArticles) {
        $scope.detail = {};

        $scope.options = {
            "image": null,

            "uploadUrl": 'http://api.vienvong.vn/photos/upload64',
            "resultUpload": {
                'photo': null,
                'thumb': null
            },

            "viewSizeWidth": 480,
            //"viewSizeHeight": 360,
            "viewSizeFixed": true,
            "viewShowFixedBtn": true,
            "viewShowRotateBtn": false,
            "viewShowCropTool": false,

            "outputImageWidth": 480,
            "outputImageHeight": 270,
            "outputImageRatioFixed": true,
            "outputImageType": "jpeg",
            "outputImageSelfSizeCrop": false,

            "watermarkType": "image",
            "watermarkImage": null,
            "watermarkText": null,
            "watermarkTextFont": "Arial",
            "watermarkTextFillColor": "rgba(0,0, 0, 0.8)",
            "watermarkTextStrokeColor": "rgba(0,0, 0, 0.8)",
            "watermarkTextStrokeLineWidth": 1,

            "inModal": false
        };

        // get articles from server
        var getDetail = function() {
            detailArticles.data({pid: $state.params.id}, {auth: $rootScope.online.code}, function(data){
                if(data.ok && data.result[0]) {
                    $scope.detail = data.result[0];
                    $scope.options.resultUpload.photo = $scope.detail.lAvatar;
                    $scope.options.resultUpload.thumb = $scope.detail.sAvatar;
                }else {
                    $state.go('main.articles.home');
                }
            });
        };

        getDetail();

        $scope.editArticles = function () {

            if($scope.options.resultUpload.photo || $scope.options.resultUpload.photo != $scope.detail.lAvatar) {
                $scope.detail.lAvatar = $scope.options.resultUpload.photo;
                $scope.detail.sAvatar = $scope.options.resultUpload.thumb;
            }

            updateArticles.update({pid: $state.params.id}, {data: $scope.detail, auth: $rootScope.online.code}, function(data) {
                if(data.ok == 1 && data.n ==1 && data.err == null) {
                    $state.go('main.articles.express', {id: $state.params.id});
                }
            })
        }
    });