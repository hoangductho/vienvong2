/**
 * Created by hoanggia on 4/3/15.
 */

'user strict';

angular
    .module('articles')
    .factory('createArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/create';

        return $resource(url,
            {},
            {
                create: {
                    method: 'POST'
                }
            }
        );
    });