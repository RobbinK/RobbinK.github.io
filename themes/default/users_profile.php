<?php
get_header();
css::loadBootStrap();
css::loadBootStrapResponsive();
css::loadAlert(false, 604800, 'shadowbox');
css::load(array(
    static_url() . '/js/jquery.showloading/showLoading.css',
    static_url() . '/js/colorbox/gebo/colorbox.css',
    static_url() . '/js/smoke/themes/abs.css'
));
$avatarUploading = convert::to_bool(ab_get_setting('members_avatar_uploading'));
$avatarMaxFileSize = ab_get_setting('members_max_avatar_filesize');
?>      
<style>
    .help-block{color:#cccccc}
    .formSep{
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px dashed #dcdcdc;
    }
</style>
<div id="content">
    <!-- Box Start -->
    <div class="single_box_outer_most_game">
        <div class="box3_wrap">
            <div class="box3_header"><?= L::forms_user_profile; ?></div>
            <div class="box_container">       

                <div id="alert"></div> 
                <form class="form_validation_ttip" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">
                        <dt><label><?= L::forms_username; ?></label></dt>
                        <dd> 
                            <strong><?= $user->username ?> </strong>

                            <div class="controls text_line"> </div>
                        </dd>
                        <?php
                        if ($avatarUploading) {
                            ?>
                            <div class="formSep"></div>
                            <dt><label for = "avatar"><?= L::forms_avatar; ?></label></dt>
                            <dd>
                                <input type = "hidden" value = "<?= encrypt($user->id) ?>" id = "edit_id"/>
                                <input type = "button" id = "upload-btn" class = "btn btn-large clearfix" value = "<?= L::forms_select_file; ?>"/>
                                <input type = "hidden" name = "avatar" id = "avatar" value = "<?= $user->avatar ?>" />
                                <span class = "help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?>: <?= $avatarMaxFileSize ?> <?= L::forms_kb; ?>)</i></span>
                                <div id = "errormsg" class = "clearfix redtext"></div>
                                <div id = "pic-progress-wrap" class = "progress-wrap" style = "margin-top:10px;margin-bottom:10px;"></div>
                                <div id = "picbox" class = "noimg clear" style = "position: relative;padding-top:0px;padding-bottom:10px;height:80px">
                                    <img class = "thumbnail" style = "width:70px;height:70px;" src = "http://www.placehold.it/70x70/EFEFEF/AAAAAA&amp;text=no+image" alt = "">
                                </div>
                            </dd>
                        <?php }
                        ?>
                        <div class="formSep"></div>
                        <dt><label for="name"><?= L::forms_full_name; ?></label></dt>
                        <dd><input type="text" id="name" name="name" value="<?= $user->name ?>" />  </dd>
                        <div class="formSep"></div>
                        <dt><label for="email"><?= L::forms_email; ?></label></dt>
                        <dd><div><input type="text" id="email" name="email" value="<?= $user->email ?>" /></div></dd>
                        <div class="formSep"></div>

                        <dt><label for="password" ><?= L::forms_old_password; ?></label></dt>
                        <dd> 
                            <input type="password" name="old_password" id="old_password"/>
                            <span class="help-block"><?= L::forms_enter_your_old_password; ?></span> 
                        </dd>
                        <dt> <label for="password" class="control-label"><?= L::forms_new_password; ?></label></dt>
                        <dd>
                            <div>
                                <input type="password" name="password" id="password"/>
                                <span class="help-block"><?= L::forms_enter_your_new_password; ?></span>
                            </div>
                        </dd>
                        <dt></dt>
                        <dd>
                            <div>
                                <input type="password" name="confirm_password" id="confirm_password"  />
                                <span class="help-block"><?= L::forms_repeat_password; ?></span>
                            </div>
                        </dd> 
                        <div class="formSep"></div> 
                        <dt></dt>
                        <dd> 
                            <button type="submit" class="btn btn-primary"><?= L::forms_save_changes; ?></button>
                            <a class="btn cancel" onclick="reset_form()"><?= L::forms_cancel; ?></a> 
                        </dd>

                    </dl>

                </form>



                <?php
                js_form_libraries();
                ?>

                <script type="text/javascript">
                    var current_avatar = '<?= $user->avatar ?>';
                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };
                    var oTable;
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
                                minlength: 5

                            },
                            email: {
                                required: true,
                                email: true
                            }
                        },
                        messages: {
                            name: "<?= L::alert_enter_name; ?>",
                            username: {
                                required: "<?= L::alert_enter_username; ?>",
                                minlength: "<?= L::alert_short_username; ?>"
                            },
                            password: {
                                required: "<?= L::alert_enter_password; ?>",
                                minlength: "<?= L::alert_short_password; ?>"
                            },
                            confirm_password: {
                                required: "<?= L::alert_repeat_password; ?>",
                                minlength: "<?= L::alert_short_password; ?>",
                                equalTo: "<?= L::alert_wrong_password; ?>"
                            },
                            email: "<?= L::alert_wrong_email; ?>",
                        },
                        debug: false,
                        onfocusout: false,
                        highlight: function(element) {
                            $(element).closest('div').addClass("f_error");
                        },
                        unhighlight: function(element) {
                            $(element).closest('div').removeClass("f_error");
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
                                data:{'encodedData':JSON.stringify(data)},
                                url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                success: function(result) {
                                    $('#myform').hideLoading();
                                    obj = JSON.parse(result);
                                    current_avatar = $('#avatar').val();
                                    if (obj.save_code === 1) {
                                        $('#alert').html(obj.save_alert);
                                        $('#myform').find("[type=password]").val('');
                                        return true;
                                    }
                                    else if (obj.save_code !== 1) {
                                        $('#alert').html(obj.save_alert);
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
                            maxSize: 5000,
                            allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                            accept: 'image/*',
                            debug: true,
                            hoverClass: 'btn-hover',
                            focusClass: 'active',
                            disabledClass: 'disabled',
                            responseType: 'json',
                            onExtError: function(filename, extension) {
                                alert('<?= L::alert_invalid_filetype; ?>');
                            },
                            onSizeError: function(filename, fileSize) {
                                alert('<?= L::alert_invalid_filesize; ?>');
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
                                btn.value = '<?= L::forms_select_another_file; ?>';
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
                            $("<img src='<?= static_url() ?>/images/loading/loader.gif' class='shoimageloading'/>").css({margin: 10}).appendTo($helpi);
                        }

                        $(jQid).fadeOut(300, function() {
                            html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '" rel="clbox" >';
                            html += '<span style="top: 0px; position: absolute; margin-left: 10px;">' + filename + '</span>';
                            html += '<button style="position: absolute; top: 23px; margin-left: 10px;" onclick="delete_avatar();return false;" class="btn-danger btn-mini deleteicon"><?= L::forms_delete; ?></button>';
                            if ($(jQid).hasClass('noimg')) {
                                $(jQid).find('img.thumbnail').hide();
                                $(jQid).find('img.thumbnail').siblings().remove();
                                $(jQid).append(html);
                                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                    $(jQid).fadeIn(300);
                                    $(jQid).parent().find('.shoimageloading').remove();
                                });
                            }
                            else {
                                $(jQid).html(html);
                                $(jQid).find('img:not(.thumbnail)').imagesLoaded(function() {
                                    $(jQid).fadeIn(300);
                                    $(jQid).parent().find('.shoimageloading').remove();
                                });
                            }
                        });
                    }

                    function reset_form() {
                        fValidation.resetForm();
                        open_uploader_picbox('#picbox', current_avatar);
                        $('#avatar').val(current_avatar);
                        $('#myform').find("[type=password]").val('');
                        $('#myform').find("#name").val('<?= $user->name ?>');
                        $('#myform').find("#name").val('<?= $user->name ?>');
                        $('#myform').find("#email").val('<?= $user->email ?>');
                    }
                    function  delete_avatar() {
                        var did = $('#edit_id').val();
                        smoke.confirm('<?= L::alert_delete_avatar; ?>', function(e) {
                            if (e) {
                                $.ajax({
                                    type: 'POST',
                                    data: {id: did},
                                    url: "<?= url::itself()->url_nonqry(array('del_avatar' => 1)) ?>",
                                    success: function(result) {
                                        close_uploader_picbox('#picbox');
                                        current_avatar = '';
                                    }
                                });
                            }
                        }, {});
                    }

                </script> 
            </div>
        </div>
    </div>
    <!-- Box End -->
</div>
<div class="clear"></div>
<?php
get_footer();
?>