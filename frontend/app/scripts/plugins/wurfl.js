/* Related devices:  */
// Copyright 2015 - ScientiaMobile, Inc., Reston, VA
// WURFL Device Detection
// Terms of service:
// http://wjs.wurflcloud.com/business-license

eval(function (p, a, c, k, e, d) {
    e = function (c) {
        return (c < a ? '' : e(parseInt(c / a))) + ((c = c % a) > 35 ? String.fromCharCode(c + 29) : c.toString(36))
    };
    if (!''.replace(/^/, String)) {
        while (c--) {
            d[e(c)] = k[c] || e(c)
        }
        k = [function (e) {
            return d[e]
        }];
        e = function () {
            return '\\w+'
        };
        c = 1
    }
    ;
    while (c--) {
        if (k[c]) {
            p = p.replace(new RegExp('\\b' + e(c) + '\\b', 'g'), k[c])
        }
    }
    return p
}('E k={"j":1,"i":"4 5 6","h":"g","f":"4 5 6","e":"","7":"","c":"","b":a,"9":2,"8":2,"l":2,"n":"m","D":3,"C":3,"B":A,"z":1,"y":1,"x":1,"v":1,"o":"u","t":"s.0","r":"q p","w":d};', 41, 41, '|false|600|400|generic|web|browser|marketing_name|max_image_width|resolution_height|800|resolution_width|manufacturer_name|null|model_name|brand_name|Desktop|form_factor|complete_device_name|is_mobile|WURFL|max_image_height|mouse|pointing_method|advertised_browser|x86_64|Linux|advertised_device_os|42|advertised_browser_version|Chrome|is_smarttv|advertised_device_os_version|is_smartphone|is_tablet|is_robot|true|is_full_desktop|physical_screen_height|physical_screen_width|var'.split('|'), 0, {}))