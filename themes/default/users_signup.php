<?php
css::loadAlert(false, 604800, 'shadowbox');
get_header(); 
js_signup_libraries();

css::load(array(
    static_url() . '/js/jquery.showloading/showLoading.css'
));
?>
<script type="text/javascript">
                    $(document).ready(function() {
                        $('#change-captcha').click(function() {
                            $('#captcha').attr('src', plugin_url + '/cool-php-captcha-0.3.1/call.php?' + Math.random());
                            $('#captcha-form').focus();
                        });
                        $('#change-captcha').trigger('click');

                        $("#signupForm").validate({
                            rules: {
                                captcha: {
                                    required: <?= convert::to_bool(ab_get_setting('members_captcha_system')) ? 'true' : 'false' ?>,
                                    remote: "<?= url::itself()->url_nonqry(array('check_captcha' => '1')) ?>"
                                },
                                name: "required",
                                uname: "required",
                                pname: {
                                    required: true,
                                    minlength: 5
                                },
                                pname2: {
                                    required: true,
                                    minlength: 5,
                                    equalTo: "#pname"
                                },
                                email: {
                                    required: true,
                                    email: true
                                },
                                agree: "required",
                            },
                            messages: {
                                captcha: "<?=L::alert_wrong_captcha;?>",
                                name: "<?=L::alert_enter_name;?>",
                                uname: {
                                    required: "<?=L::alert_enter_username;?>",
                                    minlength: "<?=L::alert_short_username;?>"
                                },
                                pname: {
                                    required: "<?=L::alert_enter_password;?>",
                                    minlength: "<?=L::alert_short_password;?>"
                                },
                                pname2: {
                                    required: "<?=L::alert_repeat_password;?>",
                                    minlength: "<?=L::alert_short_password;?>",
                                    equalTo: "<?=L::alert_wrong_password;?>"
                                },
                                email: "<?=L::alert_wrong_email;?>",
                                agree: "<?=L::alert_accept_policy;?>"
                            },
                            highlight: function(element) {
                                if ($(element).closest('p').find('em').length)
                                    $(element).closest('p').find('em').closest('div,p').addClass("f_error");
                                else
                                    $(element).closest('div').addClass("f_error");
                            },
                            unhighlight: function(element) {
                                if ($(element).closest('p').find('em').length)
                                    $(element).closest('p').find('em').closest('div,p').removeClass("f_error");
                                else
                                    $(element).closest('div').removeClass("f_error");
                            },
                            errorPlacement: function(error, element) {
                                if ($(element).closest('p').find('em').length)
                                    error.appendTo($(element).closest('p').find('em'));
                                else
                                    error.insertAfter(element);
                            },
                            submitHandler: function() {
                                $('#cboxLoadingOverlay,#cboxLoadingGraphic').show();
                                data = $.deparam($('#signupForm').serialize());

                                // checkbox 
                                $('input:checkbox').each(function() {
                                    if ($(this).is(':checked'))
                                        eval("$.extend(data || {}, {'" + $(this).attr('name') + "':'" + $(this).val() + "'});");
                                    else
                                        eval("$.extend(data || {}, {'" + $(this).attr('name') + "':''});");
                                });

                                // encode and slashes
                                // $.each(data, function (k, v) {
                                //   data[k] = base64.encode(v);
                                // });

                                $.ajax({
                                    type: 'POST',
                                    data:{'encodedData':JSON.stringify(data)},
                                    url: "<?= url::itself()->url_nonqry(array('save' => 1)) ?>",
                                    success: function(d) {
                                        result = JSON.parse(d);
                                        $('#cboxLoadingOverlay,#cboxLoadingGraphic').hide();
                                        if (result.res === 1) {
                                            $('#reg').html(result.er);
                                            $('#change-captcha').trigger('click');
                                            return true;
                                        }
                                        else {
                                            $('#reg #alert').html(result.er);
                                            $('#change-captcha').trigger('click');
                                            return false;
                                        }
                                    }
                                });
                                return false;
                            },
                            success: function(label) {
                                if (label.attr('for') == 'captcha')
                                    label.addClass("valid").text("<?=L::alert_valid_captcha;?>")
                            }
                        });
                    });
                </script>

<div id="content">
    
    <div class="single_box_outer_most_game">
        <div class="box1_wrap">
            <div class="box1_header"><?=L::forms_member_registration;?></div>
            <div class="page_container"> 

                <h1><?=L::forms_member_registration;?></h1> 
                <div id="reg">
                    <?= alert() ?>
                    <div id="alert"></div>
                    <?php
                    if (!isset($_SESSION['IsLogin']) || $_SESSION['IsLogin'] != true) {
                        ?>
                        <form method="POST" id="signupForm" name="signupForm" action="<?= url::itself()->url_nonqry() ?>">
                            <p>
                                <label for='name'><?=L::forms_name;?>: </label><br>
                                <input type="text" name="name"  id="name"  value='<?= @$name ?>'/></p>
                            <p>

                            <p>
                                <label for='uname'><?=L::forms_username;?>: </label><br>
                                <input type="text" name="uname"  id="uname" value='<?= @$uname ?>'/></p>
                            <p>
                                <label for='pname'><?=L::forms_password;?>: </label><br>
                                <input type="password" name="pname" id="pname" value='<?= @$pname ?>' />
                            </p>

                            <p>
                                <label for='pname2'><?=L::forms_repeat_password;?>: </label><br>
                                <input type="password" id="pname2" name="pname2" value='<?= @$pname ?>'>
                            </p>

                            <p>
                                <label for='email'><?=L::forms_email;?>: </label><br>
                                <input type="text" name="email"  id="email" value='<?= @$email ?>'/>
                            </p>  
                            <p style="<?= convert::to_bool(ab_get_setting('members_captcha_system')) ? '' : 'display:none' ?>" > 
                                <img src="" id="captcha" /><br/> 
                                <small><?=L::forms_cant_read_image;?> <a style="cursor: pointer;color:blue" id="change-captcha"><?=L::forms_generate_new_image;?></a></small><br/><br>            

                                <label for='message'><?=L::forms_enter_code;?>:</label><br>
                                <input   maxlength="15" name="captcha"  autocomplete="off" type="text"/><br>
                            </p>
                            <p>
                                <label for="agree"><?=L::forms_agree_privacy;?></label>
                                <input type="checkbox" class="checkbox" id="agree" name="agree" />
                            </p>
                            <input type="submit" value="<?=L::forms_submit;?>" name='submit'>
                        </form> 
                        <?php
                    }
                    else
                        echo L::alert_already_login;
                    ?> 
                </div>
            </div>
        </div>
    </div>
</div>
<div class= "clear"></div>
<?php
get_footer()
?>