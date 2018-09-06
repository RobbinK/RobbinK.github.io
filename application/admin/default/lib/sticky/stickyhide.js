/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: stickyhide.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
$(function(){
      $.stickyhide = function(id) {
        $('#' + id).slideUp('fast', function() {
            var closest = $(this).closest('.sticky-queue');
            var elem = closest.find('.sticky');
            $(this).remove();
            if (elem.length == '1') {
                closest.remove()
            }
        });
    };
});