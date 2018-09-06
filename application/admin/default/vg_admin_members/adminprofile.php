<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: adminprofile.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */

### call header
abs_admin_inc(l_basic);
abs_admin_inc(l_colorbox);
abs_admin_inc(l_validate);

abs_admin_inc(l_smoke);
abs_admin_inc(l_unserializeForm);
abs_admin_inc_js(template_path() . '/lib/simple_ajax_uploader/SimpleAjaxUploader.min.js');
get_header();
#************** 
?>
<style>
    .help-block{padding-bottom: 6px;margin-top: 0px !important}
</style>
<div id="contentwrapper">
    <div class="main_content"> 
        <!-- Navigation Menu -->
        <nav>
            <div id="jCrumbs" class="breadCrumb module">
                <ul>
                    <li>
                        <a href="<?= url::router('admindashboard'); ?>"><i class="icon-home"></i></a>
                    </li>
                    <li>
                        <?= L::header_my_profile ?>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- /Navigation Menu -->
        <div class="row-fluid">
            <div class="span12">
                <h3 class="heading"><?= L::header_my_profile ?></h3>
                <div class="row-fluid">
                    <div class="span8">
                        <form class="form_validation_ttip" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                            <input type="hidden" value="<?= $admin->id ?>" id="edit_id"/>
                            <dl class="dl-horizontal">
                                <dt><label><?= L::forms_username; ?></label></dt>
                                <dd> 
                                    <strong><?= $admin->username ?> </strong>

                                    <div class="controls text_line"> </div>
                                </dd>
                                <div class="formSep"></div>
                                <dt><label for="avatar"><?= L::forms_avatar; ?></label></dt>
                                <dd> 
                                    <input type="button" id="upload-btn" class="btn btn-large clearfix" value="<?= addslashes(L::forms_select_file);?>">
                                    <input type="hidden" name="avatar" id="avatar"  value="<?= $admin->avatar ?>" >
                                    <span class="help-inline"><i>PNG, JPG, or GIF (<?= L::forms_max_avatar_filesize ?>200K)</i></span>
                                    <div id="errormsg" class="clearfix redtext"></div>	              
                                    <div id="pic-progress-wrap" class="progress-wrap" style="margin-bottom:10px;"></div>	                    
                                    <div id="picbox" class="noimg clear" style="position: relative;padding-top:0px;padding-bottom:10px;height:80px"> 
                                        <img class="thumbnail" style="width:70px;height:70px;" src="http://www.placehold.it/70x70/EFEFEF/AAAAAA&amp;text=no+image" alt=""> 
                                    </div>
                                </dd> 
                                <div class="formSep"></div>
                                <dt><label for="name"><?= L::forms_full_name; ?></label></dt>
                                <dd>
                                    <input type="text" id="name" name="name" value="<?= $admin->name ?>" />  
                                    <em></em>
                                </dd>
                                <div class="formSep"></div>
                                <dt><label for="email"><?= L::forms_email; ?></label></dt>
                                <dd><div>
                                        <input type="text" id="email" name="email" value="<?= $admin->email ?>" />
                                        <em></em>
                                    </div></dd>
                                <div class="formSep"></div>

                                <dt><label for="password" ><?= L::forms_old_password; ?></label></dt>
                                <dd> 
                                    <div>
                                        <input type="password" name="old_password" id="old_password"/>
                                        <span class="help-block"><?= L::alert_enter_old_password; ?></span> 
                                        <em></em>
                                    </div>
                                </dd>
                                <dt> <label for="password" class="control-label"><?= L::forms_new_password; ?></label></dt>
                                <dd>
                                    <div>
                                        <input type="password" name="password" id="password"/>
                                        <span class="help-block"><?= L::alert_enter_password; ?></span>
                                        <em></em>
                                    </div>
                                </dd>
                                <dt></dt>
                                <dd>
                                    <div>
                                        <input type="password"   name="confirm_password" id="confirm_password"  />
                                        <span class="help-block"><?= L::forms_repeat_password; ?></span>
                                        <em></em>
                                    </div>
                                </dd> 
                                <div class="formSep"></div>
                                <dt></dt>
                                <dd>
                                    <button class="btn btn-inverse" type="submit"><?= L::forms_save_changes; ?></button>
                                    <a class="btn cancel" onclick="reset_form()"><?= L::global_cancel; ?></a>
                                </dd>
                            </dl>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


<?php
get_sidebar();
get_footer('_script');
?>
<script type="text/javascript">
                            var current_avatar = '<?= $admin->avatar ?>';
                            var fValidation;
                            var oTable;
                            var loading_config = {
                                'indicatorZIndex': 990,
                                'overlayZIndex': 990
                            };
                            $(document).ready(function() {
                                reg_uploaders();
                                open_uploader_picbox('#picbox', current_avatar);
                                reset_form();
                            });
                            // Validation Options
                            fValidation = $("#myform").validate({
                                rules: {
                                    password: {
                                        minlength: 5
                                    },
                                    confirm_password: {
                                        minlength: 5,
                                        equalTo: "#password"
                                    },
                                    name: {
                                        required: true,
                                    },
                                    email: {
                                        required: true,
                                        email: true
                                    }
                                },
                                messages: {
                                    username: {
                                        required: "<?= addslashes(L::alert_enter_username);?>",
                                        minlength: "<?= addslashes(L::alert_short_username);?>"
                                    },
                                    password: {
                                        required: "<?= addslashes(L::alert_provide_a_password);?>",
                                        minlength: "<?= addslashes(L::alert_short_password);?>"
                                    },
                                    confirm_password: {
                                        required: "<?= addslashes(L::alert_provide_a_password);?>",
                                        minlength: "<?= addslashes(L::alert_short_password);?>",
                                        equalTo: "<?= addslashes(L::alert_wrong_password_repeat);?>"
                                    },
                                    email: "<?= addslashes(L::alert_invalid_email);?>",
                                },
                                debug: false,
                                onfocusout: false,
                                highlight: function(element) {
                                    if ($(element).closest('dd').find('em').length)
                                        $(element).closest('dd').find('em').closest('div,dd').addClass("f_error");
                                    else
                                        $(element).closest('div').addClass("f_error");
                                },
                                unhighlight: function(element) {
                                    if ($(element).closest('dd').find('em').length)
                                        $(element).closest('dd').find('em').closest('div,dd').removeClass("f_error");
                                    else
                                        $(element).closest('div').removeClass("f_error");
                                },
                                errorPlacement: function(error, element) {
                                    if ($(element).closest('dd').find('em').length)
                                        error.appendTo($(element).closest('dd').find('em'));
                                    else
                                        error.insertAfter(element);
                                },
                                submitHandler: function(form) {
                                    $('#myform').showLoading(loading_config);
                                    data = $.deparam($('#myform').serialize());
                                    //tinymce
                                    if (typeof(tinyMCE) != 'undefined') {
                                        $('textarea.tinymce').each(function() {
                                            $tinyval = tinyMCE.get($(this).attr('id')).getContent();
                                            eval("$.extend(data || {}, {" + $(this).attr('name') + ":$tinyval});");
                                        });
                                    }

                                    // encode and slashes
                                    // $.each(data, function (k, v) {
                                    //   data[k] = base64.encode(v);
                                    // });

                                    $.ajax({
                                        type: 'POST',
                                        data:{'encodedData':encodePostData(data)},
                                        url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                        success: function(result) {
                                            $('#myform').hideLoading();
                                            obj = JSON.parse(result);
                                            current_avatar = $('#avatar').val();
                                            if (obj.save_code === 1) {
                                                $.sticky(obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                                $('#myform').find("[type=password]").val('');
                                                return true;
                                            }
                                            else if (obj.save_code !== 1) {
                                                $.sticky("<?= addslashes(L::global_error);?>! " + obj.save_txt, {autoclose: 5000, position: "top-right", type: "st-error", speed: "fast"});
                                                $('#myform').find("[type=password]").val('');
                                                return false;
                                            }

                                        }
                                    });
                                }
                            });

                            function reg_uploaders() {

                                var btn = document.getElementById('upload-btn'),
                                        wrap = document.getElementById('pic-progress-wrap'),
                                        picBox = document.getElementById('picbox'),
                                        errBox = document.getElementById('errormsg');
                                var uploader = new ss.SimpleUpload({
                                    button: btn,
                                    url: '<?= url::itself()->url_nonqry() ?>',
                                    progressUrl: '<?= url::itself()->url_nonqry() ?>',
                                    name: 'uploadfile',
                                    multiple: false,
                                    maxUploads: 2,
                                    maxSize: 200*1024,
                                    allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                                    accept: 'image/*',
                                    debug: true,
                                    hoverClass: 'btn-hover',
                                    focusClass: 'active',
                                    disabledClass: 'disabled',
                                    responseType: 'json',
                                    onExtError: function(filename, extension) {
                                        alert('<?= addslashes(L::alert_invalid_image_format);?>');
                                    },
                                    onSizeError: function(filename, fileSize) {
                                        alert('<?= addslashes(L::alert_invalid_file_size);?>. (<?= L::forms_max_file_size; ?> : 200 <?= L::global_kilo_byte ?>)');
                                    },
                                    onSubmit: function(filename, ext) {
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
                                        btn.value = '<?= addslashes(L::forms_select_another_file);?>';
                                    },
                                    onComplete: function(file, response) {
                                        if (response.success === true) {
                                            open_uploader_picbox('#picbox', response.file);
                                            $('#avatar').val(response.file);
                                        } else {
                                            errBox.innerHTML = response.msg;
                                        }
                                    }
                                });
                            }

                            function close_uploader_picbox(jQid) {
                                if ($(jQid).hasClass('noimg')) {
                                    $(jQid).find('img.thumbnail').siblings().remove();
                                    $(jQid).find('img.thumbnail').show(300);
                                } else if ($(jQid).length) {
                                    $(jQid).html('').fadeOut();
                                }
                            }
                            function open_uploader_picbox(jQid, filename) {
                                if (!$(jQid).length)
                                    return false;
                                if (filename == '' || typeof(filename) == undefined || filename == null) {
                                    close_uploader_picbox(jQid);
                                    return false;
                                }
                                $helpi = $(jQid).parent().find('span.help-inline');
                                if (!$helpi.find('.shoimageloading').length) {
                                    $("<img src='<?= static_url() ?>/images/loading/loading-9.gif' class='shoimageloading'/>").css({margin: 10}).appendTo($helpi);
                                }

                                $(jQid).fadeOut(300, function() {
                                    html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '" rel="clbox" >';
                                    html += '<span style="top: 0px; position: absolute; margin:0 10px;">' + filename + '</span>';
                                    html += '<a style="position: absolute; top: 23px; margin:0 10px;cursor:pointer;" onclick="delete_avatar();return false;" class="btn-danger btn-mini deleteicon"><?= L::global_remove ?></a>';
                                    if ($(jQid).hasClass('noimg')) {
                                        $(jQid).find('img.thumbnail').hide();
                                        $(jQid).find('img.thumbnail').siblings().remove();
                                        $(jQid).append(html);
                                        $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                            reg_colorbox('auto');
                                            $(jQid).fadeIn(300);
                                            $(jQid).parent().find('.shoimageloading').remove();
                                        });
                                    }
                                    else {
                                        $(jQid).html(html);
                                        $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                            reg_colorbox('auto');
                                            $(jQid).fadeIn(300);
                                            $(jQid).parent().find('.shoimageloading').remove();
                                        });
                                    }
                                });
                            }

                            function reg_colorbox(size) {
                                size = size || 'auto';
                                $('img[rel=clbox]').unbind('click').click(function(e) {
                                    e.stopPropagation();
                                    $.colorbox({
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
                            function reset_form() {
                                fValidation.resetForm();
                                open_uploader_picbox('#picbox', current_avatar);
                                $('#upload-btn').val('<?= addslashes(L::forms_select_file);?>');
                                $('#avatar').val(current_avatar);
                                $('#myform').find("[type=password]").val('');
                                $('#myform').find("#name").val('<?= $admin->name ?>');
                                $('#myform').find("#name").val('<?= $admin->name ?>');
                                $('#myform').find("#email").val('<?= $admin->email ?>');
                            }
                            function  delete_avatar() {
                                var did = $('#edit_id').val();
                                smoke.confirm('<?= addslashes(L::alert_del_file_warning);?>', function(e) {
                                    if (e) {
                                        st1 = $.sticky('<?= addslashes(L::alert_deleting_records);?>', {autoclose: false, position: "top-right", type: "st-info", speed: "fast"});
                                        $.ajax({
                                            type: 'POST',
                                            data: {id: did},
                                            url: "<?= url::itself()->url_nonqry(array('del_avatar' => 1)) ?>",
                                            success: function(result) {
                                                $.stickyhide(st1.id);
                                                $.sticky(result, {autoclose: 5000, position: "top-right", type: "st-success", speed: "fast"});
                                                close_uploader_picbox('#picbox');
                                                $('#avatar').val('');
                                                current_avatar = '';
                                            }
                                        });
                                    }
                                }, {});
                            }

</script>
<?php
get_footer();
?>