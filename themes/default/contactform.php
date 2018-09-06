<?php
get_header();
css::loadBootStrap();
css::loadBootStrapResponsive();
css::loadAlert(false, 604800, 'shadowbox');
css::load(array(
    static_url() . '/js/jquery.showloading/showLoading.css',
    static_url() . '/js/smoke/themes/abs.css'
));
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
    <div class="single_box_outer_most_game">

        <!-- Box Start -->
        <?= alert() ?>
        <div class="box3_wrap">
            <div class="box3_header"><?= L::menu_contact_us; ?></div>
            <div class="box_container">       

                <div id="alert"></div> 
                <form class="form_validation_ttip" id="myform" method="post" action="<?= url::itself()->fulluri() ?>"  novalidate="novalidate" enctype="multipart/form-data" onsubmit="return false">
                    <dl class="dl-horizontal">  
                        <dt><label for="name"><?= L::forms_name; ?>:</label></dt>
                        <dd><input type="text" id="name" name="name"  class="input-medium" />  </dd> 

                        <dt><label for="email"><?= L::forms_email; ?>*:</label></dt>
                        <dd><div><input type="text" id="email" name="email" class="input-medium" /></div></dd> 

                        <dt><label for="website" ><?= L::forms_website; ?>:</label></dt>
                        <dd> 
                            <input type="url" name="website" id="website" value="http://"  class="input-medium"/> 
                        </dd>
                        <dt><label for="type" ><?= L::forms_subject; ?>:</label></dt>
                        <dd>
                            <div>
                                <select name="type" id="type">
                                    <option></option>
                                    <option value="1"><?= L::forms_trade_request; ?></option>
                                    <option value="2"><?= L::forms_link_exchange_request; ?></option>
                                    <option value="3"><?= L::forms_game_exchange_reguest; ?></option>
                                    <option value="4"><?= L::forms_support; ?></option>
                                    <option value="5"><?= L::forms_other_questions; ?></option>
                                </select> 
                            </div>
                        </dd>
                        <dt><label for="comment" ><?= L::forms_message; ?>*:</label></dt>
                        <dd>
                            <div>
                                <textarea name="comment" class="auto_expand"   id="comment" style="width:330px;height: 100px;"></textarea> 
                            </div>
                        </dd> 
                        <div class="formSep"></div> 
                        <dt></dt>
                        <dd>
                            <p> 
                                <img src="" id="captcha" /><br/> 
                                <small><?= L::forms_cant_read_image; ?> <a style="cursor: pointer;color:blue" id="change-captcha"><?= L::forms_generate_new_image; ?></a></small><br/><br>            

                                <label for='message'><?= L::forms_enter_code; ?>*:</label><br>
                                <input   maxlength="15" name="captcha"  autocomplete="off" type="text"/><br>
                            </p>
                        </dd>
                        <dt></dt>
                        <dd> 
                            <button type="submit" class="btn btn-primary"><?= L::forms_send_message; ?></button>
                            <a class="btn cancel" onclick="reset_form()"><?= L::forms_cancel; ?></a> 
                        </dd>

                    </dl>

                </form>



                <?php
                js_form_libraries();
                js::load(array(
                    static_path() . '/js/jquery-smooth-scroll/jquery.smooth-scroll.min.js'
                        ), array(JS_FORCELOAD => true, JS_MINIFY => true));
                ?>

                <script type="text/javascript">
                    var fValidation;
                    var loading_config = {
                        'indicatorZIndex': 990,
                        'overlayZIndex': 990
                    };

                    $(document).ready(function() {
                        $('.auto_expand').autosize();
                        //captcha
                        $('#change-captcha').click(function() {
                            $('#captcha').attr('src', plugin_url + '/cool-php-captcha-0.3.1/call.php?' + Math.random());
                            $('#captcha-form').focus();
                        });
                        $('#change-captcha').trigger('click');
                    });
                    // Validation Options
                    jQuery.validator.addMethod("url", function(value, element) {
                        return this.optional(element) || /^(https?:\/\/)?((localhost|[a-z0-9\-]+(\.[a-z0-9\-]+)+)(:[0-9]+)?(\/.*)?)?$/.test(value);
                    }, "<?= L::alert_invalid_url; ?>");
                    fValidation = $("#myform").validate({
                        rules: {
                            captcha: {
                                required: true,
                                remote: "<?= url::itself()->url_nonqry(array('check_captcha' => '1')) ?>"
                            },
                            comment: {
                                minlength: 10,
                                required: true
                            },
                            email: {
                                required: true,
                                email: true
                            },
                            type: {
                                required: true
                            }
                        },
                        messages: {
                            captcha: "<?= L::alert_wrong_captcha; ?>",
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
                                    if (obj.save_code == 1) {
                                        $('#alert').html(obj.save_alert);
                                        $('#change-captcha').trigger('click');
                                        reset_form();
                                        return true;
                                    }
                                    else if (obj.save_code != 1) {
                                        $('#alert').html(obj.save_alert);
                                        return false;
                                    }
                                    $.smoothScroll({
                                        scrollElement: $('body'),
                                        scrollTarget: '#alert',
                                        speed: 500
                                    });
                                }
                            });
                        },
                        success: function(label) {
                            if (label.attr('for') == 'captcha')
                                label.addClass("valid").text("<?= L::alert_valid_captcha; ?>")
                        }
                    });

                    function reset_form() {
                        $('#myform').find('input:text, input[type=url],input[type=hidden], input:password, input:file, select, textarea').val('');
                        $('#myform').find('input:radio, input:checkbox').removeAttr('checked').removeAttr('selected');
                        //tinymce
                        if (typeof(tinyMCE) != 'undefined') {
                            $('textarea.tinymce').each(function() {
                                tinyMCE.get($(this).attr('id')).setContent('');
                                tinyMCE.DOM.setStyle(tinyMCE.DOM.get($(this).attr('id') + '_ifr'), 'height', 120 + 'px');
                            });
                        }
                        //select
                        $('#myform').find('select').each(function() {
                            $(this).find('option:first').attr('selected', 'true');
                        });

                        //date
                        $('#myform').find('input').each(function() {
                            if ($(this).closest('div').attr('data-date-format')) {
                                format = ($(this).closest('div').data('date-format')).toLowerCase().replace('yyyy', 'yy');
                                newd = $.datepicker.formatDate(format, new Date());
                                $(this).val(newd);
                            }
                        });
                        //default
                        $('#myform').find('input').each(function() {
                            if ($(this).attr('data-default')) {
                                $(this).val($(this).data('default'));
                            }
                        });
                    }

                </script> 
            </div>
        </div>
        <!-- Box End -->
    </div>
</div>
<div class="clear"></div>
<?php
get_footer();
?>