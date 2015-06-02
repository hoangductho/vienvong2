/**
 * Created by hoanggia on 3/28/15.
 */

'use strict';

angular
    .module('articles')
    .factory('detailArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/detail/:pid';

        return $resource(url,
            {
                pid: '@id'
            },
            {
                data: {
                    method: 'POST'
                }
            }
        );
    })
    .factory('updateArticles', function ($resource, $rootScope) {
        var url = $rootScope.apiHost + '/articles/edit/:pid';

        return $resource(url,
            {
                pid: '@id'
            },
            {
                update: {
                    method: 'POST'
                    //params:{update:true}
                }
            }
        );
    });