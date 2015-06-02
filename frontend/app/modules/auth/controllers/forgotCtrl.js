/**
 * Created by hoanggia on 4/17/15.
 */

angular
    .module('auth')
    .controller('forgotCtrl', function ($scope, $sce) {
        var auth = {
            email: null
        };

        var validate = {
            email: {
                regexp: "[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$",
                mess: ''
            }
        };

        var insertedInit = {
            email: 0
        };

        var inserted = angular.copy(insertedInit);

        $scope.validate = angular.copy(validate);
        $scope.auth = angular.copy(auth);

        $scope.scores = 0;

        $scope.valid = function(feild) {
            var patten = new RegExp($scope.validate[feild].regexp);
            var res = patten.test($scope.auth[feild]);

            if(res) {
                $scope.validate[feild].mess = $sce.trustAsHtml('<div class="valid form-control"><i class="fa fa-check-circle"></i> valid</div>');

                if(!inserted[feild]) {
                    $scope.scores += 1;
                    inserted[feild] = 1;
                }

            }else {
                $scope.validate[feild].mess = $sce.trustAsHtml('<div class="invalid form-control"><i class="fa fa-times-circle"></i> invalid</div>');

                if(inserted[feild]) {
                    $scope.scores -= 1;
                    inserted[feild] = 0;
                }
            }

        };

        $scope.reset = function () {
            $scope.validate = angular.copy(validate);
            $scope.auth = angular.copy(auth);
            inserted = angular.copy(insertedInit);
            $scope.scores = 0;
        }
    });