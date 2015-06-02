/**
 * Created by hoanggia on 3/22/15.
 */

'use strict';

angular
    .module('articles')
    .factory('getArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/filter/:group/:page';

        return $resource(url,
            {
                group: '@group',
                page: '@page'
            },
            {});
    })
    .factory('hotArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/focus';
        return $resource(url, {}, {});
    });