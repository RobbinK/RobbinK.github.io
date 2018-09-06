/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pshowlimit.jquery.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */
var pShowLimit = function (el, options) {
    this.el = el;
    this.maxWords = options.maxWords || -1;
    this.maxChars = options.maxChars || -1;
    this._toggle = function (el) {
        if ($(el).val().length > 0)
            $(el).next('.limit-hint').fadeIn(1000);
        else
            $(el).next('.limit-hint').fadeOut(1000);
    };

    $this = this;
    $this.keyuped = 0;
    $this._toggle(el);
    _tkeyup = function (el) {
        var mthis = $this;
        mthis.keyuped = 1;
        mthis.el = el;
        mthis.show = function () {
            var wordcount;
            mthis._toggle(mthis.el);
            wordcount = $(mthis.el).val().split(/\b[\s,\.-:;]*/).length;
            $text = "Characters length: " + $(mthis.el).val().length; 
            if (mthis.maxWords > 0) {
                if ($text != '')
                    $text += " , ";
                $text += "Words: " + wordcount;
            }
            $(mthis.el).next(".limit-hint").find('span').text($text);
            if ((mthis.maxWords > 0 && wordcount > mthis.maxWords) || (mthis.maxChars > 0 && $(mthis.el).val().length > mthis.maxChars))
                $(mthis.el).next(".limit-hint").find('span').css({color: '#C62626', fontWeight: 'bold'});
            else
                $(mthis.el).next(".limit-hint").find('span').css({color: 'green', fontWeight: 'normal'});
        }
        return mthis.show;
    };
    $(this.el).keyup(new _tkeyup(el));
    $this.lastval = null;
    setInterval(new function () {
        var mthis = $this;
        return  function () {
            if (typeof (mthis.keyuped) == 'defined')
            {
                mthis.keyuped = 0;
                mthis.lastval = $(mthis.el).val();
            }
            if ($(mthis.el).val() != mthis.lastval) {
                mthis.lastval = $(mthis.el).val();
                mthis.show();
            }
        }
    }, 1000);

};