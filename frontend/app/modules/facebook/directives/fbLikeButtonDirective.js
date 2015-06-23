/**
 * Created by hoanggia on 6/22/15.
 */
angular
    .module('facebook')
    .directive('fbLikeButton', function ($compile) {
        return {
            restrict: 'C',
            scope: {
            },
            link: function (scope, element, attrs) {
                var observer = function(link) {
                    // build markdown editor template.
                    var newElement = $compile(
                        '<div class="fb-like" data-href="' + link + '"'
                       +'data-layout="button_count" data-action="like" data-show-faces="false" data-share="true">'
                       +'</div>')(scope);

                    // add markdown editor in to point called it. html() doesn't work
                    element.replaceWith(newElement);

                };
                attrs.$observe('link', observer);
            }
        }
    });