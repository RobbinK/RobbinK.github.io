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
?> 

<div id="content">
    <!-- Box Start -->
    <div class="single_box_outer_most_game">
        <div class="box3_wrap">
            <div class="box3_header"><?= L::menu_submit_your_games; ?></div>
            <div class="box_container">       

                <div id="reg">
                    <div id="alert"></div> 
                    <form class="form_validation_reg" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                        <dl class="dl-horizontal">
                            <dt><label><?= L::forms_game_name; ?>:</label></dt>
                            <dd> 
                                <div>
                                    <input type="text" name="game_name" id="game_name"  required /> 
                                </div>
                            </dd> 

                            <dt><label> <?= L::forms_genres; ?>:</label></dt>
                            <dd><div>
                                    <select name="game_categories" id="game_categories"  style="width:300px" >
                                        <?php
                                        while ($cat = $cats->fetch())
                                            echo "<option>{$cat->title}</option>";
                                        ?>
                                    </select>                                    
                                </div>
                            </dd>

                            <dt><label><?= L::forms_description; ?>:</label></dt>
                            <dd><div><textarea  name="game_description" id="game_description" class="input-xxlarge auto_expand"></textarea></div></dd>

                            <dt><label><?= L::forms_instruction; ?>:</label></dt>
                            <dd><div><textarea  name="game_instruction" id="game_instruction" class="input-xxlarge auto_expand"></textarea></div></dd>

                            <dt><label><?= L::forms_game_controls; ?>:</label></dt> 
                            <dd><div><textarea  name="game_controls" id="game_controls" class="input-xxlarge auto_expand"></textarea></div></dd>

                            <dt><label><?= L::forms_tags; ?>:</label></dt>
                            <dd><div><textarea name="game_tags" id="game_tags" class="input-xxlarge auto_expand"></textarea>
                                    <span class="help-inline"><?= L::forms_separated_with_comma; ?></span>
                                </div>
                            </dd>
                            <div class="formSep"></div>
                            <!--Thumb 100x100-->
                            <dt><label><?= L::forms_game_image; ?>:</label></dt>
                            <dd id="game_img_wrapper">
                                <input type="hidden" name="game_img" id="game_img"   />
                                <div class="manual">
                                    <input type="button" id="upload_game_img" class="btn btn-large clearfix" value="Select File" style="margin: 0"/>
                                    <span class="help-inline"><i>PNG, JPG, GIF (<?= L::forms_max_file_size; ?>: 200 <?= L::forms_kb; ?>)</i></span>
                                </div> 
                                <div id="game_img_attachment" style="margin-bottom:15px;">
                                    <div id="errormsg-game_img" class="clearfix redtext"></div>	              
                                    <div id="pic-progress-wrap-game_img" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;"></div>	
                                    <div id="filebox-game_img" class="clear" style="position: relative;padding-top:0px;padding-bottom:10px;"></div>
                                </div>
                            </dd>

                            <!--Game File-->
                            <dt><label><?= L::forms_game_file; ?>:</label></dt>
                            <dd id="game_file_wrapper">
                                <input type="hidden" name="game_file" id="game_file">
                                <div class="manual">
                                    <input type="button" id="upload_game_file" class="btn btn-large clearfix" value="<?= L::forms_select_file; ?>"  style="margin: 0"/>
                                    <span class="help-inline"><i>SWF, DCR, UNITY3D (<?= L::forms_max_file_size; ?>: <?= L::forms_mb; ?>)</i></span>
                                </div>
                                <div id="game_file_attachment"  style="margin-bottom:15px;">
                                    <div id="errormsg-game_file" class="clearfix redtext"></div>	              
                                    <div id="pic-progress-wrap-game_file" class="progress-wrap" style="margin-top:10px;margin-bottom:10px;"></div>	
                                    <div id="filebox-game_file" class="clear" style="position: relative;height:60px;padding-top:0px;padding-bottom:10px;"></div>
                                </div>
                            </dd> 

                            <div class="formSep"></div>
                            <dt><label><?= L::forms_game_width; ?>:</label></dt>
                            <dd><div>
                                    <input type="text" name="width" id="width"  style="width:50px" required>
                                    <span class='help-inline'><?= L::forms_px; ?></span>
                                </div>
                                <em></em>
                            </dd>

                            <dt><label><?= L::forms_game_height; ?>:</label></dt>
                            <dd><div>
                                    <input type="text" name="height" id="height" style="width:50px"  required>
                                    <span class='help-inline'><?= L::forms_px; ?></span>
                                </div>
                                <em></em>
                            </dd>  

                            <dt></dt>
                            <dd>
                                <p> 
                                    <img src="" id="captcha" class="r5" /><br/> 
                                    <small><?= L::forms_cant_read_image; ?> <a style="cursor: pointer;" id="change-captcha"><?= L::forms_generate_new_image; ?></a></small><br/><br>            

                                    <label for='message'><?= L::forms_enter_code; ?>*:</label><br>
                                    <input   maxlength="15" name="captcha"  autocomplete="off" type="text"/><br>
                                </p>
                            </dd>

                            <dt></dt>
                            <dd> 
                                <div>
                                    <input type="submit" class="btn btn-success" value="<?= L::forms_submit; ?>" style="width: 94px;height: 34px;" name='submit'> 
                                </div>
                            </dd>
                        </dl>
                    </form> 
                </div>
                <?php
                js_form_libraries();
                ?>
                <script type="text/javascript">
                        var debug = true;
                        var fValidation;
                        var oTable;
                        var loading_config = {
                            'indicatorZIndex': 990,
                            'overlayZIndex': 990
                        };

                        $(document).ready(function() {
                            reg_xhr_abortAll();
                            reg_uploaders_images('game_img');
                            reg_uploaders_game_file();
                            reset_form();


                            //captcha
                            $('#change-captcha').click(function() {
                                $('#captcha').attr('src', plugin_url + '/cool-php-captcha-0.3.1/call.php?' + Math.random());
                                $('#captcha-form').focus();
                            });
                            $('#change-captcha').trigger('click');
                        });

                        function reset_form() {
                            $('.form_validation_reg').find('input:text, input[type=url],input[type=email], input[type=hidden], input:password, input:file, select, textarea').val('');
                            $('.form_validation_reg').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                            //tinymce
                            if (typeof(tinyMCE) != 'undefined') {
                                $('textarea.tinymce').each(function() {
                                    tinyMCE.get($(this).attr('id')).setContent('');
                                    tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
                                });
                            }

                            //select
                            $('.form_validation_reg').find('select').each(function() {
                                $(this).find('option:first').attr('selected', 'true');
                            });
                            //default
                            $('.form_validation_reg').find('input').each(function() {
                                if ($(this).attr('data-default')) {
                                    $(this).val($(this).data('default'));
                                }
                            });


                            //date
                            $('.form_validation_reg').find('input').each(function() {
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


                            //multipleSelect
                            if (typeof $.fn.multipleSelect != 'undefined') {
                                $("select[multiple='multiple']").multipleSelect("uncheckAll");
                            }

                            //filebox
                            close_uploader_box('#filebox-game_img');
                            close_uploader_box('#filebox-game_file');
                            $('#upload_game_img').val('Select File');
                            $('#upload_game_file').val('Select File');
                            fValidation.resetForm();
                            $('.form_validation_reg div').removeClass("f_error");
                        }



                        // Validation Options
                        fValidation = $("#myform").validate({
                            debug: false,
                            onfocusout: false,
                            rules: {
                                captcha: {
                                    required: true,
                                    remote: "<?= url::itself()->url_nonqry(array('check_captcha' => '1')) ?>"
                                }
                            },
                            messages: {
                                captcha: "<?= L::alert_wrong_captcha; ?>"
                            },
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
                                //checkbox 
                                $('input:checkbox').each(function() {
                                    if ($(this).is(':checked'))
                                        eval("$.extend(data || {}, {'" + $(this).attr('name') + "':'" + $(this).val() + "'});");
                                    else
                                        eval("$.extend(data || {}, {'" + $(this).attr('name') + "':''});");
                                });

                                //multipleSelect
                                if (typeof $.fn.multipleSelect != 'undefined') {
                                    $("select[multiple='multiple']").each(function() {
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
                                    data:{'encodedData':JSON.stringify(data)},
                                    url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                    success: function(result) {
                                        $('#myform').hideLoading();
                                        obj = JSON.parse(result);
                                        if (obj.save_code === 1) {
                                            $('#reg').html(obj.save_txt);
                                            reset_form();
                                            return true;
                                        }
                                        else {
                                            $('#reg #alert').html(obj.save_txt);
                                            return false;
                                        }
                                    }
                                });
                            }
                        });


                        function reg_xhr_abortAll() {
                            $.xhrPool = [];
                            $.xhrPool.abortAll = function() {
                                $(this).each(function(idx, jqXHR) {
                                    jqXHR.abort();
                                });
                                $.xhrPool.length = 0
                            };

                            $.ajaxSetup({
                                beforeSend: function(jqXHR) {
                                    $.xhrPool.push(jqXHR);
                                },
                                complete: function(jqXHR) {
                                    var index = $.inArray(jqXHR, $.xhrPool);
                                    if (index > -1) {
                                        $.xhrPool.splice(index, 1);
                                    }
                                }
                            });
                        }

                        function abortAllAjax() {
                            $.xhrPool.abortAll();
                            $('.loading-indicator-overlay,.loading-indicator').remove();
                            $('.shoimageloading').remove();
                            $('.dataTables_processing').css({visibility: 'hidden'});
                        }


                        function reg_uploaders_images(nn) {
                            var btn = document.getElementById('upload_' + nn),
                                    wrap = document.getElementById('pic-progress-wrap-' + nn),
                                    filebox = document.getElementById('filebox-' + nn),
                                    errBox = document.getElementById('errormsg-' + nn);
                            var uploader = new ss.SimpleUpload({
                                button: btn,
                                url: '<?= url::itself()->url_nonqry() ?>',
                                progressUrl: '<?= url::itself()->url_nonqry() ?>',
                                name: 'up_img_' + nn,
                                multiple: false,
                                maxUploads: 2,
                                maxSize: 200,
                                allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
                                accept: 'image/*',
                                debug: false,
                                hoverClass: 'btn-hover',
                                focusClass: 'active',
                                disabledClass: 'disabled',
                                responseType: 'json',
                                onExtError: function(filename, extension) {
                                    alert("<?= L::alert_invalid_image_format; ?>");
                                },
                                onSizeError: function(filename, fileSize) {
                                    alert("<?= L::alert_invalid_filesize; ?>");
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
                                        open_uploader_imagebox('#filebox-' + nn, nn, response.file);
                                        $('#' + nn).val(response.file);
                                    } else {
                                        errBox.innerHTML = response.msg;
                                        $('#' + nn).val('');
                                    }
                                }
                            });
                        }

                        function reg_uploaders_game_file() {
                            var btn = document.getElementById('upload_game_file'),
                                    wrap = document.getElementById('pic-progress-wrap-game_file'),
                                    filebox = document.getElementById('filebox-game_file'),
                                    errBox = document.getElementById('errormsg-game_file');
                            var uploader = new ss.SimpleUpload({
                                button: btn,
                                url: '<?= url::itself()->url_nonqry() ?>',
                                progressUrl: '<?= url::itself()->url_nonqry() ?>',
                                name: 'up_game_file',
                                multiple: false,
                                maxUploads: 2,
                                maxSize: 1024 * 30,
                                allowedExtensions: ['swf', 'dcr', 'unity3d'],
                                accept: 'flash/*',
                                debug: false,
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
                                        open_uploader_filebox_game_file('#filebox-game_file', 'game_file', response.file);
                                        $('#game_file').val(response.file);
                                    } else {
                                        errBox.innerHTML = response.msg;
                                        $('#game_file').val('');
                                    }
                                }
                            });
                        }

                        function close_uploader_box(jQid) {
                            if (debug)
                                console.log('close_uploader_box\n');
                            if ($(jQid).hasClass('noimg')) {
                                $(jQid).find('img.thumbnail').siblings().remove();
                                $(jQid).find('img.thumbnail').show();
                            } else if ($(jQid).length) {
                                $(jQid).html('').fadeOut();
                            }

                        }
                        function open_uploader_imagebox(jQid, dbField, filename) {
                            if (!$(jQid).length)
                                return false;
                            if (filename == '' || typeof(filename) == undefined || filename == null) {
                                close_uploader_box(jQid);
                                return false;
                            }
                            $helpi = $(jQid).parent().find('span.help-inline');
                            if (!$helpi.find('.shoimageloading').length) {
                                $("<img src='<?= static_url() ?>/images/loading/loader.gif' class='shoimageloading'/>").css({margin: 10}).appendTo($helpi);
                            }

                            $(jQid).fadeOut(300, function() {
                                html = '<img src="<?= url::itself()->url_nonqry() ?>?showimage=' + encodeURIComponent(filename) + '" rel="clbox" >';
                                html += '<span style="top: 0px; position: absolute; margin-left: 10px;">' + filename + '</span>';
                                //                            html += '<a style="position: absolute; top: 23px; margin-left: 10px;cursor:pointer;" onclick="delete_file(\'' + dbField + '\');return false;" class="btn-danger btn-mini deleteicon">Delete</a>';
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
                                        $(jQid).fadeIn(300);
                                        $(jQid).parent().find('.shoimageloading').remove();
                                    });
                                }
                            });
                        }
                        function open_uploader_filebox_game_file(jQid, dbField, filename) {
                            if (!$(jQid).length)
                                return false;
                            if (filename == '' || typeof(filename) == undefined || filename == null) {
                                $(jQid).html('').fadeOut();
                                return false;
                            }

                            $(jQid).fadeOut(300, function() {
                                act_btn = '<div style="position: absolute; top: 23px; margin-left: 10px;cursor:pointer;" class="btn-group">';
                                act_btn += '<a class="btn btn-inverse btn-mini dropdown-toggle"  data-toggle="dropdown" href="#"  ><?= L::forms_action; ?><span class="caret"></span></a>';
                                act_btn += '<ul class="dropdown-menu">';
                                act_btn += '<li><a href="' + filename + '" class="showswf"><i class="icon-eye-open"></i> <?= L::forms_preview; ?></a></li>';
                                act_btn += '<li><a href="#" class="pop_over" data-container="body" data-placement="right" data-original-title="<?= L::forms_get_dimension; ?>"><i class="icon-road"></i> <?= L::forms_get_dimension; ?></a></li>';
                                act_btn += '<li><a href="#" onclick="delete_file(\'' + dbField + '\');return false;"><i class="icon-trash"></i> <?= L::forms_delete_file; ?></a></li></ul></div>';
                                html = '<img src="<?= static_url() ?>/images/icons/attachments/attachment_up.png">';
                                html += '<span style="top: 0px; position: absolute; margin-left: 10px;">' + summarize(filename, 25, true, '') + '</span>';
                                //                            html += act_btn;
                                $(jQid).html(html);
                                $(jQid).fadeIn(300);
                                $(".pop_over").popover({trigger: 'hover'});
                                reg_showswf_colorbox();
                            });
                        }


                        function  delete_file(db_field) {
                            var did = $('#myform .edit_id').val();
                            smoke.confirm('<?= L::alert_confirm_delete; ?>', function(e) {
                                if (e) {
                                    pid = $('#' + db_field).parent().find("div[id^='filebox']").attr('id');
                                    $('#' + db_field).val('');
                                    close_uploader_box('#' + pid);
                                }
                            }, {});
                        }

                        function reg_colorbox(size) {
                            size = size || 'auto';
                            $('.dataTable img[rel=clbox]').unbind('click').click(function(e) {
                                e.stopPropagation();
                                $.colorbox({
                                    href: $(this).data('fullimage'),
                                    photo: true,
                                    maxWidth: '90%',
                                    maxHeight: '90%',
                                    opacity: '0.2',
                                    loop: false,
                                    fixed: true
                                });

                            });

                            $('#myform img[rel=clbox]').unbind('click').click(function(e) {
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

                        function reg_showswf_colorbox() {
                            $('a.showswf').unbind('click').click(function() {
                                var s = $(this).attr('href');
                                if ($(s).length)
                                    s = $(s).val();
                                $.colorbox({
                                    href: '<?= url::itself()->url_nonqry() ?>?showswf=' + encodeURIComponent(s),
                                    maxWidth: '98%',
                                    maxHeight: '98%',
                                    opacity: '0.2',
                                    loop: false,
                                    fixed: true
                                });
                                return false;
                            });
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