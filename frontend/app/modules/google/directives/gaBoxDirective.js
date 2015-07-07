/**
 * Created by hoanggia on 6/26/15.
 */

/**
 * Created by hoanggia on 6/20/15.
 */

angular
    .module('google')
    .directive("gaBox", function ($window, $compile) {
        var modulePath = 'modules/google/';
        return {
            restrict: 'C',
            replace: true,
            scope: {
            },
            templateUrl: modulePath + 'views/ggAdsense.html',
            /*link: function (scope, element, attrs) {
                var observer = function(aid) {
                    if(
                        (attrs.minscreen && $window.innerWidth >= attrs.minscreen)
                        || (attrs.maxscreen && $window.innerWidth < attrs.maxscreen)
                        || (!attrs.maxscreen && !attrs.minscreen)
                    ) {
             if(typeof(adsbygoogle) == "undefined") {
             jQuery.ajax({
             type: "GET",
             url: "http://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js",
             success: function(){},
             dataType: "script",
             cache: true
             });
             }
                        // build markdown editor template.
                        var newElement = $compile(
                            '<ins class="adsbygoogle"'
                            + 'style="display:block"'
                            + 'data-ad-client="'+ attrs.publisher +'"'
                            + 'data-ad-slot="' + aid + '"'
                            + 'data-ad-format="auto"></ins>'
                            )(scope);

                        // add markdown editor in to point called it. html() doesn't work
                        element.replaceWith(newElement);
                    }
                };

                attrs.$observe('aid', observer);
            },*/
            controller: function () {
                (adsbygoogle = window.adsbygoogle || []).push({});
            }
        }
    });