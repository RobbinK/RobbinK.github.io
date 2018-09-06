/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: detect_cookie_jx.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */
function ab_are_cookies_enabled()
{
    var cookieEnabled = (navigator.cookieEnabled) ? true : false;
    if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
    {
        document.cookie = 'testcookie';
        cookieEnabled = (document.cookie.indexOf('testcookie') != -1) ? true : false;
    }
    return (cookieEnabled);
}

if (ab_are_cookies_enabled())
    data = 'detected_cookie=1';
else
    data = 'detected_cookie=0';
var url = "<?=url::router('homepage');?>";
if (typeof jQuery != 'undefined') {
    $.get(url + '?' + data);
} else if (typeof jx != 'undefined') {
    jx.load(url + '?' + data);
}