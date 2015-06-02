/**
 * Created by hoanggia on 3/24/15.
 */

'use strict';

angular
    .module('articles')
    .factory('expressArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/express/:pid';

        return $resource(url,
            {
                pid: '@id'
            },
            {}
        );
    });