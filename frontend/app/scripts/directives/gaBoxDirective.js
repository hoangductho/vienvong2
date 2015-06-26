/**
 * Created by hoanggia on 6/26/15.
 */

/**
 * Created by hoanggia on 6/20/15.
 */

angular
    .module('vienvong')
    .directive("gaBox", function ($window, $compile) {
        return {
            restrict: 'C',
            scope: {
            },
            link: function (scope, element, attrs) {
                var observer = function(aid) {
                    if(
                        (attrs.minscreen && $window.innerWidth >= attrs.minscreen)
                        || (attrs.maxscreen && $window.innerWidth < attrs.maxscreen)
                        || (!attrs.maxscreen && !attrs.minscreen)
                    ) {
                        // build markdown editor template.
                        var newElement = $compile(
                            '<ins class="adsbygoogle"'
                            + 'style="display:block"'
                            + 'data-ad-client="ca-pub-5355896671501389"'
                            + 'data-ad-slot="' + aid + '"'
                            + 'data-ad-format="auto"></ins>'
                            + '<script>'
                            +   '(adsbygoogle = window.adsbygoogle || []).push({});'
                            + '</script>')(scope);

                        // add markdown editor in to point called it. html() doesn't work
                        element.replaceWith(newElement);
                    }
                };

                attrs.$observe('aid', observer);
            }
        }
    });