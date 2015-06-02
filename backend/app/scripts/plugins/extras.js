/**
 * Created by hoanggia on 4/29/15.
 */

function randomKey(number) {
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for( var i=0; i < number; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));

    return text;
}