/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: common.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */
function pengu_message_close(el) {
    if (!window.jQuery)  
        el.parentNode.style.display = 'none';
    else
        $(el).parent().animate({opacity: 0}, 400, function() {
            $(this).hide();
        });
}