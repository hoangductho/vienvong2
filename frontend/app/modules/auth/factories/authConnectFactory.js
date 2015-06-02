/**
 * Created by hoanggia on 4/21/15.
 */

'use strict';

angular
    .module('auth')
    .factory('authConnect', function ($resource) {
        return function(url) {
            return $resource(url,
                {},
                {
                    submit: {
                        method: 'POST'
                    }
                }
            )
        };
    });