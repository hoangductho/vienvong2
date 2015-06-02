/**
 * Created by hoanggia on 3/16/15.
 *
 * Script Initialize Asynchronous Facebook API
 */

'use strict';

window.fbAsyncInit = function () {
    FB.init({
        appId: '550251971759267',
        status: true,
        xfbml: true,
        version: 'v2.1'
    });
};

(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=550251971759267&version=v2.1";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

$(document).ajaxComplete(function(){
    try{
        FB.XFBML.parse();
    }catch(ex){}
});