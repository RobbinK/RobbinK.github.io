/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: comment.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */
var pengu_comment_url = '<?=plugin_url()?>/pengu_comment';
var bbcode_url = '<?=plugin_url()?>/pengu_comment/bbeditor';
$(function() {
    install_reply_event();
    install_edit_event();
    comment_form_reset(); //new form and load data cookie
    if (dback)
        comment_form_fillfrompost(); //load data from post

    //== reload    
    var cu = $(location).attr('href');
    queryparam = $.deparam.querystring(cu);
    if (typeof queryparam.creplyto != 'undefined')
    {
        replyid = base64.decode(queryparam.creplyto);
        setTimeout(function() {
            $.smoothScroll({
                scrollElement: $('body'),
                scrollTarget: '#declaration',
                speed: 500,
                afterScroll: function() {
                    set_replyto(replyid);
                }
            });
        }, 300);

    }
    else if (typeof queryparam.cedit != 'undefined')
    {
        editid = base64.decode(queryparam.cedit);
        setTimeout($.smoothScroll({
            scrollElement: $('body'),
            scrollTarget: '#declaration',
            speed: 500,
            afterScroll: function() {
                set_edit(editid);
                return false;
            }
        }), 300);
    } else if ($('#declaration div').length) {
        setTimeout(function() {
            $.smoothScroll({
                scrollElement: $('body'),
                scrollTarget: '#declaration',
                speed: 500
            });
        }, 300);
    }

});
//*********************************
//===== Reply
function install_reply_event() {
    $('.comment .ctools .replyLink').click(function() {
        var reply_id = $(this).parent().parent().parent().find('#cid').html();
        comment_form_new();
        set_replyto(reply_id);
        setTimeout(function() {
            $.smoothScroll({
                scrollElement: $('body'),
                scrollTarget: '#declaration'
            });
        }, 300);
        return false;
    });
}

function set_replyto(reply_id) {
    var _name = $('.comment').find('#cm_' + reply_id + ' #cname').html();
    comment_form_urlparam({creplyto: base64.encode(reply_id)});
    comment_form_puthidden('comment_form_replyto', reply_id);
    comment_caption("You are replying to  <strong>" + _name + "</strong><a class='cancel1'  onclick=\"comment_form_new()\"></a>");
}
//*********************************
//===== Edit
function install_edit_event() {
    $('.comment .ctools .edit').click(function() {
        comment_form_new();
        var edit_id = $(this).parent().parent().parent().find('#cid').html();
        var _name = $('.comment').find('#cm_' + edit_id + ' #cname').html();
        var _email = $('.comment').find('#cm_' + edit_id + ' #cemail').html();
        var _website = $('.comment').find('#cm_' + edit_id + ' #cwebsite').html();
        var _comment = $('.comment').find('#cm_' + edit_id + ' #ccomment').html();
        $('#comment_form #comment_form_name').val(_name);
        $('#comment_form #comment_form_website').val(_email);
        $('#comment_form #comment_form_email').val(_website);
        comment_bbcode_html(_comment);
        set_edit(edit_id);
        setTimeout(function() {
            $.smoothScroll({
                scrollElement: $('body'),
                scrollTarget: '#declaration'
            });
        }, 300);
        return false;
    });
}

function set_edit(edit_id) {
    comment_form_urlparam({cedit: base64.encode(edit_id)});
    comment_form_puthidden('comment_form_editid', edit_id);
    comment_caption("You are editing comment <a class='cancel1'  onclick=\"comment_form_new()\"></a>");
}


//******************************
//******************************
//Other functions

function comment_form_new() {
    $('#declaration').html('');
    $('#commentreplytoname').remove();
    comment_form_reset();
}

function comment_form_reset() {
    comment_form_fillfromcookie();
    comment_bbcode_html('');
    comment_form_puthidden('comment_form_replyto', '');
    comment_form_puthidden('comment_form_editid', '');
    comment_form_urlparam({});
}

function comment_caption(html) {
    html = html || '';
    if ($('#commentreplytoname').length == 0 && html != '') {
        $('#comment_form').before("<p id='commentreplytoname' style='width:200px;text-indent: 18px;position:relative;'>" + html + "</p>");
    } else if (html != '') {
        $('#commentreplytoname').html(html);
    } else
        $('#commentreplytoname').remove();
}

function comment_from_mode(mode) {
    mode = mode || '';
    if ($('#comment_form').find('#mode').length == 0 && mode != '') {
        el = $("<input type='hidden' id='mode' value='" + mode + "'/>");
        $(el).appendTo($('#comment_form'));
        return true;
    } else if (mode != '') {
        $('#comment_form').find('#mode').val(mode);
        return true;
    } else if (mode == '') {
        return  $('#comment_form').find('#mode').val() || 'new';
    }
}

function comment_form_urlparam(data, override) {
    var oldparams = new Object();
    data = data || {};
    override = override || false;
    url = $('#comment_form').attr('action');
    if (url.indexOf('?') != -1) {
        var myparams = $.deparam.querystring(url);
        var ignnored = ['creplyto', 'cedit'];
        for (var key in myparams)
            if ($.inArray(key, ignnored) == -1) //not found 
                eval("oldparams." + key + "=myparams[key];");
    }
    if (!override)
        data = $.extend(data, oldparams);
    url = $.param.querystring(url, data, 2);
    $('#comment_form').attr('action', url);
}

function comment_form_puthidden(name, val) {
    if ($('#comment_form').find('#' + name).length == 0 && val != '') {
        el = $("<input type='hidden' id='" + name + "' name='" + name + "' value='" + val + "'/>");
        $(el).appendTo($('#comment_form'));
    } else if (val != '') {
        $('#comment_form').find('#' + name).val(val);
    } else if (val == '') {
        $('#comment_form').find('#' + name).remove();
    }
}



function comment_form_submit() {
    $('.comment_err').remove();
    comment_err = function(id, msg) {
        $("<p></p>").html(msg).insertAfter('#' + id).addClass('comment_err');
    }

    var error = 0;
    if ($("#comment_form_name").val() == '') {
        comment_err('comment_form_name', "enter your name.");
        error = 1;
    }

    if (!validateEmail($("#comment_form_email").val())) {
        comment_err('comment_form_email', email + "Email is not valid!");
        error = 1;
    }

    if (error)
        return false;
    return true;
}

function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
//*********************************
//===== BBCODE
function comment_bbcode_html(html) {
    $('textarea#comment_form_comment').html(html);
    try {
        bbeditor_destroy("textarea#comment_form_comment");
    } catch (e) {
    }
    bbeditor_install("textarea#comment_form_comment");
}
function bbeditor_destroy(el) {
    $(el).sceditor("instance").destroy();
}
function bbeditor_insert(el, value) {
    $(el).sceditor("instance").insert(value);
}
function bbeditor_install(el) {
    var initEditor = function() {
        $(el).sceditor({
            plugins: 'bbcode',
            emoticonsRoot: bbcode_url + '/',
            style: bbcode_url + "/minified/jquery.sceditor.default.min.css",
            toolbar: "bold,italic,underline" +
                    ",color," +
                    "emoticon|",
            size: "1,2,3"
        });
    }; 
    initEditor();
}