/**
 * Created by hoanggia on 6/9/15.
 */

'use strict';

angular
    .module('group')
    .factory('groupConnect', function ($resource) {
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