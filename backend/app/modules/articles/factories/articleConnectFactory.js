/**
 * Created by hoanggia on 5/30/15.
 */

'use strict';

angular
    .module('articles')
    .factory('articleConnect', function ($resource) {
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