/**
 * Created by hoanggia on 4/26/15.
 */

angular
    .module('vienvong')
    .factory('initConnect', function ($resource) {
        return function (url) {
            return $resource(url,
                {},
                {
                    init: {
                        method: 'POST'
                    }
                }
            )
        };
    });