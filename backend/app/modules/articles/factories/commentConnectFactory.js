/**
 * Created by hoanggia on 5/23/15.
 */

'use strict';

angular
    .module('articles')
    .factory('commentConnect', function ($resource) {
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