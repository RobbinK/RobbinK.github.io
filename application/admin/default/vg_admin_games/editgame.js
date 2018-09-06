/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: editgame.js
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

var __debug = true;
var __fValidation;
var __loading_config = {
    'indicatorZIndex': 990,
    'overlayZIndex': 990
};


function __reg_xhr_setup() {
    $.xhrPool = [];
    $.xhrPool.abortAll = function () {
        $(this).each(function (idx, jqXHR) {
            jqXHR.abort();
        });
        $.xhrPool.length = 0
    };

    $.ajaxSetup({
        beforeSend: function (jqXHR) {
            $.xhrPool.push(jqXHR);
        },
        complete: function (jqXHR) {
            var index = $.inArray(jqXHR, $.xhrPool);
            if (index > -1) {
                $.xhrPool.splice(index, 1);
            }
        }
    });
    $.ajaxSetup({
        error: function(x, e) {
            if (x.status == 500) {
                alert('Internel Server Error.');
                __abortAllAjax();
            }
        }
    });
}

function __abortAllAjax() {
    $.xhrPool.abortAll();
    $('#expressform .shoimageloading').remove();
}


function __reset_form() {
    if (__debug)
        console.log('trigger: __resetform() \n');
    $('#expressform').find('input:text, input[type=url], input[type=hidden], input:password, input:file, select, textarea').val('');
    $('#expressform').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
    //tinymce
    if (typeof (tinyMCE) != 'undefined') {
        $('#expressform textarea.tinymce').each(function () {
            tinyMCE.get($(this).attr('id')).setContent('');
            tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
        });
    }
    //select
    $('#expressform').find('select').each(function () {
        $(this).find('option:first').attr('selected', 'true');
        $(this).trigger('change');
    });
    //default
    $('#expressform').find('input').each(function () {
        if ($(this).attr('data-default')) {
            $(this).val($(this).data('default'));
        }
    });

    //date
    $('#expressform').find('input').each(function () {
        if ($(this).closest('div').attr('data-date-format')) {
            if (!$("#" + $(this).attr('id') + "[data-default]").length) {
                format = ($(this).closest('div').data('date-format')).toLowerCase().replace('yyyy', 'yy');
                t = new Date();
                newd = $.datepicker.formatDate(format, t);
                $(this).val(newd);
            } else {
                $(this).val($(this).data('default'));
            }
        }
    });

    if (typeof $.fn.multipleSelect != 'undefined') {
        $("select[multiple='multiple']").multipleSelect("uncheckAll");
    }
    //filebox
    __close_uploader_box('#expressform #filebox-game_img');
    __close_uploader_box('#expressform #filebox-featured_img');
    __close_uploader_box('#expressform #filebox-game_file');
    __close_uploader_box('#filebox-game_slide_image');
    $('#expressform #upload_game_img').val(window.forms_select_file);
    $('#expressform #upload_featured_img').val(window.forms_select_file);
    $('#expressform #upload_game_file').val(window.forms_select_file);
    $('#expressform #upload-game_slide_image').val(window.forms_select_file);

    $('#expressform #game_url_parameters_box').fadeOut();

    $('#expressform .uploaderror').html('');

    if (typeof __fValidation != 'undefined')
        __fValidation.resetForm();
    $('#expressform div').removeClass("f_error");

    $('.auto_expand').each(function () {
        $(this).css({'height': $(this).data('default-height'), 'min-height': $(this).data('default-height')});
    });
    $('#game_tags').tagsinput('removeAll');
}

function __trigger_selectuploaders() {
    $('#game_file_source').trigger('change');
    $('#game_image_source').trigger('change');
    $('#game_show_slide:checkbox').trigger('change');
}



function __reg_uploaders_game_img() {
    var btn = document.getElementById('upload_game_img'),
            wrap = document.getElementById('pic-progress-wrap-game_img'),
            filebox = document.getElementById('filebox-game_img'),
            errBox = document.getElementById('errormsg-game_img');
    var uploader = new ss.SimpleUpload({
        button: btn,
        url: window.editgame_url,
        progressUrl: window.editgame_url,
        name: 'up_game_img',
        multiple: false,
        maxUploads: 2,
        maxSize: 200 * 1024,
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        accept: 'image/*',
        debug: false,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onChange: function () {
            this.setData({
                gid: $('#expressform .edit_id').val(),
                gamename: encodeURIComponent($('#expressform #game_name').val())
            });
        },
        onExtError: function (filename, extension) {
            alert(widnow.alert_invalid_image_format);
        },
        onSizeError: function (filename, fileSize) {
            alert(window.alert_invalid_file_size);
        },
        onSubmit: function (filename, ext) {
            var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;
            prog.className = 'prog';
            size.className = 'size';
            outer.className = 'progress progress-info input-medium';
            bar.className = 'bar';
            outer.appendChild(bar);
            prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
            prog.appendChild(size);
            prog.appendChild(outer);
            wrap.appendChild(prog); // 'wrap' is an element on the page

            self.setProgressBar(bar);
            self.setProgressContainer(prog);
            self.setFileSizeBox(size);
            errBox.innerHTML = '';
            btn.value = window.forms_select_another_file;
        },
        onComplete: function (file, response) {
            if (response.success === true) {
                __open_uploader_imagebox('#filebox-game_img', 'game_img', response.file);
                $('#game_img').val(response.file);
            } else {
                errBox.innerHTML = response.msg;
                $('#game_img').val('');
            }
        }
    });
}

function __reg_uploaders_featured_img() {

    var btn = document.getElementById('upload_featured_img'),
            wrap = document.getElementById('pic-progress-wrap-featured_img'),
            filebox = document.getElementById('filebox-featured_img'),
            errBox = document.getElementById('errormsg-featured_img');
    var uploader = new ss.SimpleUpload({
        button: btn,
        url: window.editgame_url,
        progressUrl: window.editgame_url,
        name: 'up_featured_img',
        multiple: false,
        maxUploads: 2,
        maxSize: 200 * 1024,
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        accept: 'image/*',
        debug: false,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onChange: function () {
            this.setData({
                gid: $('#expressform .edit_id').val(),
                gamename: encodeURIComponent($('#expressform #game_name').val())
            });
        },
        onExtError: function (filename, extension) {
            alert(widnow.alert_invalid_image_format);
        },
        onSizeError: function (filename, fileSize) {
            alert(window.alert_invalid_file_size);
        },
        onSubmit: function (filename, ext) {
            var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;
            prog.className = 'prog';
            size.className = 'size';
            outer.className = 'progress progress-info input-medium';
            bar.className = 'bar';
            outer.appendChild(bar);
            prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
            prog.appendChild(size);
            prog.appendChild(outer);
            wrap.appendChild(prog); // 'wrap' is an element on the page

            self.setProgressBar(bar);
            self.setProgressContainer(prog);
            self.setFileSizeBox(size);
            errBox.innerHTML = '';
            btn.value = window.forms_select_another_file;
        },
        onComplete: function (file, response) {
            if (response.success === true) {
                __open_uploader_imagebox('#filebox-featured_img', 'featured_img', response.file);
                $('#featured_img').val(response.file);
            } else {
                errBox.innerHTML = response.msg;
                $('#featured_img').val('');
            }
        }
    });
}

function __reg_uploaders_slideshow_img() {

    var btn = document.getElementById('upload_game_slide_image'),
            wrap = document.getElementById('pic-progress-wrap-game_slide_image'),
            filebox = document.getElementById('filebox-game_slide_image'),
            errBox = document.getElementById('errormsg-game_slide_image');
    var uploader = new ss.SimpleUpload({
        button: btn,
        url: window.editgame_url,
        progressUrl: window.editgame_url,
        name: 'up_game_slide_image',
        multiple: false,
        maxUploads: 1,
        allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
        accept: 'image/*',
        debug: false,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onChange: function () {
            this.setData({
                gid: $('#expressform .edit_id').val(),
                gamename: encodeURIComponent($('#expressform #game_name').val())
            });
        },
        onExtError: function (filename, extension) {
            alert(widnow.alert_invalid_image_format);
        },
        onSubmit: function (filename, ext) {
            var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;
            prog.className = 'prog';
            size.className = 'size';
            outer.className = 'progress progress-info input-medium';
            bar.className = 'bar';
            outer.appendChild(bar);
            prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
            prog.appendChild(size);
            prog.appendChild(outer);
            wrap.appendChild(prog); // 'wrap' is an element on the page

            self.setProgressBar(bar);
            self.setProgressContainer(prog);
            self.setFileSizeBox(size);
            errBox.innerHTML = '';
            btn.value = window.forms_select_another_file;
        },
        onComplete: function (file, response) {
            if (response.success === true) {
                __open_uploader_imagebox('#filebox-game_slide_image', 'game_slide_image', response.file);
                $('#game_slide_image').val(response.file);
            } else {
                errBox.innerHTML = response.msg;
                $('#game_slide_image').val('');
            }
        }
    });
}

function __reg_uploaders_game_file() {

    var btn = document.getElementById('upload_game_file'),
            wrap = document.getElementById('pic-progress-wrap-game_file'),
            filebox = document.getElementById('filebox-game_file'),
            errBox = document.getElementById('errormsg-game_file');
    var uploader = new ss.SimpleUpload({
        button: btn,
        url: window.editgame_url,
        progressUrl: window.editgame_url,
        name: 'up_game_file',
        multiple: false,
        maxUploads: 2,
        maxSize: 1024 * 1024 * 50,
        allowedExtensions: ['swf', 'dcr', 'unity3d'],
        /*accept: 'flash/*',*/
        debug: false,
        hoverClass: 'btn-hover',
        focusClass: 'active',
        disabledClass: 'disabled',
        responseType: 'json',
        onChange: function () {
            this.setData({
                gid: $('#expressform .edit_id').val(),
                gamename: encodeURIComponent($('#expressform #game_name').val())
            });
        },
        onExtError: function (filename, extension) {
            alert(widnow.alert_invalid_image_format);
        },
        onSizeError: function (filename, fileSize) {
            alert(window.alert_invalid_file_size);
        },
        onSubmit: function (filename, ext) {
            var prog = document.createElement('div'),
                    outer = document.createElement('div'),
                    bar = document.createElement('div'),
                    size = document.createElement('div'),
                    self = this;
            prog.className = 'prog';
            size.className = 'size';
            outer.className = 'progress progress-info input-medium';
            bar.className = 'bar';
            outer.appendChild(bar);
            prog.innerHTML = '<span style="vertical-align:middle;">' + strip_tags(filename) + ' - </span>';
            prog.appendChild(size);
            prog.appendChild(outer);
            wrap.appendChild(prog); // 'wrap' is an element on the page

            self.setProgressBar(bar);
            self.setProgressContainer(prog);
            self.setFileSizeBox(size);
            errBox.innerHTML = '';
            btn.value = window.forms_select_another_file;
        },
        onComplete: function (file, response) {
            if (response.success === true) {
                __open_uploder_filebox_game_file('#filebox-game_file', 'game_file', response.file);
                $('#game_file').val(response.file);
                if (window.get_auto_game_dimension && response.width > 0)
                    $('#game_width').val(response.width);
                if (window.get_auto_game_dimension && response.height > 0)
                    $('#game_height').val(response.height);
            } else {
                errBox.innerHTML = response.msg;
                $('#game_file').val('');
                if (window.get_auto_game_dimension) {
                    $('#game_width').val('0');
                    $('#game_height').val('0');
                }
            }
        }
    });
}
function __close_uploader_box(jQid) {
    if (__debug)
        console.log('close_uploder_imagebox\n');
    if ($(jQid).hasClass('noimg')) {
        $(jQid).find('img.thumbnail').siblings().remove();
        $(jQid).find('img.thumbnail').show();
    } else if ($(jQid).length) {
        $(jQid).html('').fadeOut();
    }
}

function __open_uploader_imagebox(jQid, dbField, filename) {
    if (__debug)
        console.log('open_uploder_imagebox\n');
    if (!$(jQid).length)
        return false;
    if (filename == '' || typeof (filename) == undefined || filename == null) {
        __close_uploader_box(jQid);
        return false;
    }
    if (!$(jQid).parent().find('.shoimageloading').length) {
        $(jQid).before("<img src='" + window.static_url + "/images/loading/loading-9.gif' class='shoimageloading'/>");
    }

    $(jQid).fadeOut(300, function () {
        html = '<img src="' + window.editgame_url + '?showimage=' + encodeURIComponent(filename) + '&size=75xauto" rel="clbox" >';
        html += '<span style="top: 0px; position: absolute; margin:0 10px;">' + summarize(filename, 25, true, '') + '</span>';
        html += '<a style="position: absolute; top: 23px; margin:0 10px;cursor:pointer;" onclick="__delete_file(\'' + dbField + '\',\'' + filename + '\');return false;" class="btn-danger btn-mini deleteicon">' + window.global_remove + '</a>';
        if ($(jQid).hasClass('noimg')) {
            $(jQid).find('img.thumbnail').hide();
            $(jQid).find('img.thumbnail').siblings().remove();
            $(jQid).append(html);
            $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                __reg_colorbox('auto');
                $(jQid).fadeIn(300);
                $(jQid).parent().find('.shoimageloading').remove();
            });
        }
        else {
            $(jQid).html(html);
            $(jQid).find('img:not(.thumbnail)').imagesLoaded(function () {
                __reg_colorbox('auto');
                $(jQid).fadeIn(300);
                $(jQid).parent().find('.shoimageloading').remove();
            });
        }
    });
}
function __open_uploder_filebox_game_file(jQid, dbField, filename, doneFunc) {
    if (__debug)
        console.log('trigger:  __open_uploder_filebox_game_file()\n');

    if (!$(jQid).length)
        return false;
    if (filename == '' || typeof (filename) == undefined || filename == null) {
        $(jQid).html('').fadeOut();
        return false;
    }

    $(jQid).fadeOut(300, function () {
        act_btn = '<div style="margin:0 10px;cursor:pointer;" class="btn-group">';
        act_btn += ' <a class="btn btn-primary btn-mini dropdown-toggle"  data-toggle="dropdown" href="#"  >' + window.global_action + '<span class="caret"></span></a>';
        act_btn += ' <ul class="dropdown-menu">';
        act_btn += '  <li><a href="#" class="showextparameters"><i class="icon-briefcase"></i> Set extra parameters</a></li>';
        act_btn += '  <li><a href="' + filename + '" class="showswf"><i class="icon-eye-open"></i> Preview</a></li>';
        act_btn += '  <li><a href="#" onclick="__delete_file(\'' + dbField + '\',\'' + filename + '\');return false;"><i class="icon-trash"></i>' + window.global_remove + '</a></li>';
        act_btn += ' </ul>';
        act_btn += '</div>';

        html = '<img src="' + window.static_url + '/images/icons/attachments/attachment_up.png" class="pull-left"/>';
        html += '<div class="pull-left">';
        html += ' <span style="margin:0 10px 3px;display:block;">' + summarize(filename, 25, true, '');
        html += '  <span class="label label-default" id="game_url_parameters_box" style="display: none;">?';
        html += '   <input type="text" id="game_url_parameters"  name="game_url_parameters" class="input-medium" placeholder="parameters.." style="margin: 0;"/>';
        html += '   <span class="close remove_ext_paramas" title="Remove parameters" style="margin: 5px 0 0 2px">×</span>';
        html += '  </span>';
        html += ' </span>';
        html += act_btn;
        html += '</div>';

        $(jQid).html(html);
        $(jQid).fadeIn(300);
        $(".pop_over").popover({trigger: 'hover'});
        __reg_showswf_colorbox();
        $('#expressform .showextparameters').click(function () {
            $('#expressform #game_url_parameters_box').fadeIn();
            $('#expressform .showextparameters').closest('.btn-group').removeClass('open');
            return false;
        });
        $('#expressform .remove_ext_paramas').click(function () {
            $('#expressform #game_url_parameters_box').fadeOut(function () {
                $('#expressform #game_url_parameters').val('');
            });
            return false;
        });
        if (typeof doneFunc != 'undefined')
            doneFunc();
    });
}

function  __delete_file(db_field, filename) {
    var did = $('#expressform .edit_id').val();
    smoke.confirm(window.alert_del_file_warning, function (e) {
        if (e) {
            st1 = $.sticky(window.alert_deleting_file, {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
            $.ajax({
                type: 'POST',
                data: {'id': did, 'db_field': db_field, 'filename': filename},
                url: window.editgame_url + '?del_file=1',
                success: function (result) {
                    $.stickyhide(st1.id);
                    obj = JSON.parse(result);
                    if (obj.delete_code === 1)
                        $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                    else
                        $.sticky(obj.delete_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                    pid = $('#' + db_field).parent().find("div[id^='filebox']").attr('id');
                    $('#' + db_field).val('');
                    __close_uploader_box('#' + pid);
                }
            });
        }
    }, {});
}

function __reg_colorbox(size) {
    size = size || 'auto';
    $('#expressform img[rel=clbox]').each(function () {
        $(this).colorbox({
            href: $(this).attr('src') + '&size=' + size,
            photo: true,
            maxWidth: '90%',
            maxHeight: '90%',
            opacity: '0.2',
            loop: false,
            fixed: true
        });
    });
}
function __reg_showswf_colorbox() {
    $('a.showswf').unbind('click').click(function () {
        var s = $(this).attr('href');
        try {
            if ($(s).length)
                s = $(s).val();
        } catch (e) {
        }
        $.colorbox({
            href: window.editgame_url + '?showswf=' + encodeURIComponent(s),
            maxWidth: '98%',
            maxHeight: '98%',
            opacity: '0.2',
            loop: false,
            fixed: true
        });
        return false;
    });
}


function __grabbing_game_file(callback) {
    var did = $('#expressform .edit_id').val();
    var gamename = $('#expressform #game_name').val();
    if (($('#grab_game_file').val() == $('#grab_game_file').data('default')) || ($('#grab_game_file').val() == ''))
        return false;
    if (typeof (callback) != 'undefined')
        window.callbackjob++;
    var file_addr = $('#grab_game_file').val();
    st1 = $.sticky('<?= addslashes(L::alert_grabbing_file);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
    if (!$('#grab_game_file').closest('.grab').find('.shoimageloading').length) {
        $('#grab_game_file').closest('.grab').append("<img src='" + window.static_url + "/images/loading/loading-9.gif' class='shoimageloading'/>");
    }
    // encode and slashes
    data = {'id': did, 'from': file_addr, 'gamename': gamename};
    $.each(data, function (k, v) {
        data[k] = encodeURIComponent(v);
    });
    $.ajax({
        type: 'POST',
        data: data,
        url: window.editgame_url + '?act_grab_game_file=1',
        success: function (result) {
            $('#grab_game_file').closest('.grab').find('.shoimageloading').remove();
            $.stickyhide(st1.id);
            obj = JSON.parse(result);
            if (obj.grab_code === 1) {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                if (typeof (callback) == 'undefined') {
                    __open_uploder_filebox_game_file('#filebox-game_file', 'game_file', obj.file);
                }
                $('#game_file').val(obj.file);
                if (window.get_auto_game_dimension && obj.width > 0)
                    $('#game_width').val(obj.width);
                if (window.get_auto_game_dimension && obj.height > 0)
                    $('#game_height').val(obj.height);
            }
            else {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                $('#filebox-game_file').html('').fadeOut();
                $('#game_file').val('');
                if (window.get_auto_game_dimension) {
                    $('#game_width').val('0');
                    $('#game_height').val('0');
                }
            }
            $('#grab_game_file').val($('#grab_game_file').data('default') || '');
            if (typeof (callback) != 'undefined') {
                //                if (__debug)
//                    console.log('job upload game file is completed.');
                window.callbackjob--;
                eval(callback + '();');
            }

        }

    });
    return true;
}

function __grabbing_game_img(callback) {
    var did = $('#expressform .edit_id').val();
    var gamename = $('#expressform #game_name').val();
    if (($('#grab_game_img').val() == $('#grab_game_img').data('default')) || ($('#grab_game_img').val() == ''))
        return false;
    if (typeof (callback) != 'undefined')
        window.callbackjob++;
    var file_addr = $('#grab_game_img').val();
    st2 = $.sticky(window.alert_grabbing_file, {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
    if (!$('#grab_game_img').closest('.grab').find('.shoimageloading').length) {
        $('#grab_game_img').closest('.grab').append("<img src='" + window.static_url + "/images/loading/loading-9.gif' class='shoimageloading'/>");
    }
    // encode and slashes
    data = {'id': did, 'from': file_addr, 'gamename': gamename};
    $.each(data, function (k, v) {
        data[k] = encodeURIComponent(v);
    });
    $.ajax({
        type: 'POST',
        data: data,
        url: window.editgame_url + '?act_grab_game_img=1',
        success: function (result) {
            $('#grab_game_img').closest('.grab').find('.shoimageloading').remove();
            $.stickyhide(st2.id);
            obj = JSON.parse(result);
            if (obj.grab_code === 1) {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                if (typeof (callback) == 'undefined') {
                    __open_uploader_imagebox('#filebox-game_img', 'game_img', obj.file);
                }
                $('#game_img').val(obj.file);
            }
            else {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                $('#filebox-game_img').html('').fadeOut();
                $('#game_img').val('');
            }
            $('#grab_game_img').val($('#grab_game_img').data('default') || '');
            if (typeof (callback) != 'undefined') {
                //                if (__debug)
//                    console.log('job upload game file is completed.');
                window.callbackjob--;
                eval(callback + '();');
            }
        }
    });
}

function __grabbing_featured_img(callback) {
    var did = $('#expressform .edit_id').val();
    var gamename = $('#expressform #game_name').val();
    if (($('#grab_featured_img').val() == $('#grab_featured_img').data('default')) || ($('#grab_featured_img').val() == ''))
        return false;
    if (typeof (callback) != 'undefined')
        window.callbackjob++;
    var file_addr = $('#grab_featured_img').val();
    st3 = $.sticky(window.alert_grabbing_file, {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
    if (!$('#grab_featured_img').closest('.grab').find('.shoimageloading').length) {
        $('#grab_featured_img').closest('.grab').append("<img src='" + window.static_url + "/images/loading/loading-9.gif' class='shoimageloading'/>");
    }
    // encode and slashes
    data = {'id': did, 'from': file_addr, 'gamename': gamename};
    $.each(data, function (k, v) {
        data[k] = encodeURIComponent(v);
    });
    $.ajax({
        type: 'POST',
        data: data,
        url: window.editgame_url + '?act_grab_featured_img=1',
        success: function (result) {
            $('#grab_featured_img').closest('.grab').find('.shoimageloading').remove();
            $.stickyhide(st3.id);
            obj = JSON.parse(result);
            if (obj.grab_code === 1) {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                if (typeof (callback) == 'undefined') {
                    __open_uploader_imagebox('#filebox-featured_img', 'featured_img', obj.file);
                }
                $('#featured_img').val(obj.file);
            }
            else {
                $.sticky(obj.grab_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                $('#filebox-featured_img').html('').fadeOut();
                $('#featured_img').val('');
            }
            $('#grab_featured_img').val($('#grab_featured_img').data('default') || '');
            if (typeof (callback) != 'undefined') {
                if (__debug)
                    console.log('job upload game file is completed.');
                window.callbackjob--;
                eval(callback + '();');
            }
        }
    });
}

function __editform(eid) { 
    if (__debug)
        console.log('trigger:  __editform()\n');
//    __reset_form();
    $('#gedit-modal').showLoading(loading_config);

    $.ajax({
        type: 'POST',
        data: {'id': eid},
        url: window.editgame_url + '?edit=1',
        success: function (result) {
            $('#gedit-modal').hideLoading();
            data = JSON.parse(result);
            $('#expressform').unserializeForm($.param(data));
            if (typeof (tinyMCE) != 'undefined') {
                $.each(data, function (k, v) {
                    if ($('textarea.tinymce[name=' + k + ']').length) {
                        id = $('textarea.tinymce[name=' + k + ']').attr('id');
                        tinyMCE.get(id).setContent(v);
                    }
                });
            }
            if (typeof $.fn.multipleSelect != 'undefined') {
                $("select[multiple='multiple']").each(function () {
                    if (data[$(this).attr('name')].length > 0)
                        $(this).multipleSelect("setSelects", data[$(this).attr('name')]);
                });
            }

            if (typeof (data.game_tags) != 'undefined') {
                for (i = 0; i < data.game_tags.length; i++)
                    $("#game_tags").tagsinput('add', data.game_tags[i]);
            }
            $('#expressform #ribbon_type').trigger('change');
            setTimeout(function () {
                if (typeof (data.game_img) != 'undefined' && data.game_img != '') {
                    $('#game_img').val(data.game_img);
                    __open_uploader_imagebox('#filebox-game_img', 'game_img', data.game_img);
                }

                if (typeof (data.featured_img) != 'undefined' && data.featured_img != '') {
                    $('#featured_img').val(data.featured_img);
                    __open_uploader_imagebox('#filebox-featured_img', 'featured_img', data.featured_img);
                }

                if (typeof (data.game_slide_image) != 'undefined' && data.game_slide_image != '') {
                    $('#game_slide_image').val(data.game_slide_image);
                    __open_uploader_imagebox('#filebox-game_slide_image', 'game_slide_image', data.game_slide_image);
                }
                if ((data.game_file_source == 0 || data.game_file_source == 1) && typeof (data.game_file) != 'undefined' && data.game_file != '') {
                    $('#game_file').val(data.game_file);
                    __open_uploder_filebox_game_file('#filebox-game_file', 'game_file', data.game_file, function () {
                        if (typeof (data.game_url_parameters) != 'undefined' && data.game_url_parameters != '') {
                            $('#expressform #game_url_parameters').val(data.game_url_parameters);
                            $('#expressform #game_url_parameters_box').fadeIn();
                        }
                    });
                }
            }, 200);
        }
    });
}
function __reg_validation() {
    // Validation Options
    window.callbackjob = 0;
    jQuery.validator.addMethod("url", function (value, element) {
        return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
    }, window.alert_invalid_link);

    __fValidation = $("#expressform").validate({
        debug: false,
        onfocusout: false,
        highlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
            else
                $(element).closest('div').addClass("f_error");
        },
        unhighlight: function (element) {
            if ($(element).closest('dd').find('em').length)
                $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
            else
                $(element).closest('div').removeClass("f_error");
        },
        errorPlacement: function (error, element) {
            if ($(element).closest('dd').find('em').length)
                error.appendTo($(element).closest('dd').find('em'));
            else
                error.insertAfter(element);
        },
        submitHandler: function (form) {
            window.submitmyform = function () {
                if (window.callbackjob > 0)
                    return false;
                $('#gedit-modal').showLoading(__loading_config);
                data = $.deparam($('#expressform').serialize());
                //tinymce
                if (typeof (tinyMCE) != 'undefined') {
                    $('textarea.tinymce').each(function () {
                        $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                    });
                }

                if (typeof $.fn.multipleSelect != 'undefined') {
                    $("select[multiple='multiple']").each(function () {
                        $multis = $(this).multipleSelect("getSelects");
                        eval("$.extend(data || {}, {" + $(this).attr('name') + ":$multis});");
                    });
                }
                // encode and slashes
                // $.each(data, function (k, v) {
                //   data[k] = base64.encode(v);
                // });

                $.ajax({
                    type: 'POST',
                    data:{'encodedData':encodePostData(data)},
                    url: window.editgame_url + '?save=1',
                    success: function (result) {
                        $('#gedit-modal').hideLoading();
                        obj = JSON.parse(result);
                        if (obj.save_code === 1) {
                            $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                            $('#gedit-modal').modal('hide');
                            return true;
                        }
                        else {
                            $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                            return false;
                        }

                    }
                });
            };
            if ($('#game_image_source').val() == 1) {
                __grabbing_game_img('window.submitmyform');
                __grabbing_featured_img('window.submitmyform');
            }
            if ($('#game_file_source').val() == 1) {
                __grabbing_game_file('window.submitmyform');
            }
            window.submitmyform();
        }
    });

}
function __reg_tagsinput() {
    $('#game_tags').tagsinput({
        confirmKeys: [13],
        typeahead: {
            source: function (query) {
                return $.getJSON(window.editgame_url + '?gettags=' + query);
            }
        }
    });
}
__bodyLoad = function () {
    if (__debug)
        console.log('trigger:  __bodyload()\n');
    $("#expressform select[multiple='multiple']").multipleSelect({selectAllText: window.global_select_all});
    __reg_xhr_setup();
    __reg_uploaders_game_img();
    __reg_uploaders_featured_img();
    __reg_uploaders_slideshow_img();
    __reg_uploaders_game_file();
    __reg_showswf_colorbox();
    __reg_validation();
    __reg_tagsinput();
    __reset_form();
    $('#expressform .auto_expand').autosize();
    //label
    $('#expressform #ribbon_expiration').spinner({
        min: 1
    });
    $('#expressform #ribbon_type').change(function () {
        if ($('#expressform #ribbon_type').val())
            $('#expressform #ribbon_expiration_wrapper').fadeIn();
        else
            $('#expressform #ribbon_expiration_wrapper').fadeOut();
    });
    $('#expressform #ribbon_type').trigger('change');
    // Image Source
    $('#expressform #game_image_source').on('change', function () {
        if (__debug)
            console.log('trigger:  game_image_source (val:' + $(this).val() + ')\n');

        $('#expressform #game_img_wrapper').find('.manual,.grab').each(function () {
            $(this).fadeOut(300);
        });
        $('#expressform #featured_img_wraper').find('.manual,.grab').each(function () {
            $(this).fadeOut(300);
        });
        if ($(this).val() == 1)
            $('#expressform #game_img_wrapper .grab,#featured_img_wraper .grab').delay(300).fadeIn(300);
        else
            $('#expressform #game_img_wrapper .manual,#featured_img_wraper .manual').delay(300).fadeIn(300);

    });

    // File Source
    $('#expressform #game_file_source').on('change', function () {
        if (__debug)
            console.log('trigger:  game_file_source (val:' + $(this).val() + ')\n');

        $('#expressform #game_file_wraper').find('.manual,.grab,.iframe,.link,.embedded').each(function () {
            $(this).fadeOut(300);
        });
        switch ($(this).val()) {
            case '1':
                $('#expressform #game_file_wraper .grab').delay(300).fadeIn(300);
                $('#expressform #game_file_attachment').delay(300).fadeIn(300);
                break;
            case '0':
                $('#expressform #game_file_wraper .manual').delay(300).fadeIn(300);
                $('#expressform #game_file_attachment').delay(300).fadeIn(300);
                break;
            case '2':
                $('#expressform #game_file_wraper .iframe').delay(300).fadeIn(300);
                $('#expressform #game_file_attachment').delay(300).fadeOut(300);
                break;
            case '3':
                $('#expressform #game_file_wraper .link').delay(300).fadeIn(300);
                $('#expressform #game_file_attachment').delay(300).fadeOut(300);
                break;
            case '4':
                $('#expressform #game_file_wraper .embedded').delay(300).fadeIn(300);
                $('#expressform #game_file_attachment').delay(300).fadeOut(300);
                break;
        }

        $('.bt_cancel').click(function () {
            $('#gedit-modal').modal('hide');
            __abortAllAjax();
        });
    });
    // slideshow 
    $('#expressform #game_show_slide:checkbox').change(function () {
        if ($(this).is(':checked'))
            $('#expressform #game_slide_image_wrapper').fadeIn(300);
        else
            $('#expressform #game_slide_image_wrapper').fadeOut(300);
    });
    __trigger_selectuploaders();
}
