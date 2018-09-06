/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: newjavascript.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */


(function ($) {
    this._toggle = function (el) {
        if ($(el).val().length > 0)
            $(el).next('.limit-hint').fadeIn(1000);
        else
            $(el).next('.limit-hint').fadeOut(1000);
    };

    var _show = function () {
        var that = this;
//            var wordcount;
        console.log(that.val());
        return;
//            mthis._toggle(mthis.el);
        wordcount = $(that).val().split(/\b[\s,\.-:;]*/).length;
        $text = '';
        if (that.options.maxChars > 0)
            $text = "Characters length: " + $(that).val().length;

        if (that.options.maxWords > 0) {
            if ($text != '')
                $text = +" , ";
            $text += "Keywords: " + wordcount;
        }
        $(that).next(".limit-hint").find('span').text($text);
        if ((that.options.maxWords > 0 && wordcount > that.options.maxWords) || (that.options.maxChars > 0 && $(that).val().length > that.options.maxChars))
            $(that).next(".limit-hint").find('span').css({color: '#C62626', fontWeight: 'bold'});
        else
            $(that).next(".limit-hint").find('span').css({color: 'green', fontWeight: 'normal'});
    };

    $.fn.limiting = function (options) {
        that = this;
        that.options = $.extend({
            maxWords: -1,
            maxChars: -1
        }, options);

        that.bind('keyup', function () {
            new _show();
        });
    }
}(jQuery));