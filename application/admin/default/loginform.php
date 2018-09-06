<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: loginform.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:55
##########################################################
 */

css::load(template_url() . '/bootstrap/css/bootstrap.' . (lang_isrtl() ? 'rtl' : 'min') . '.css');
css::load(template_url() . '/bootstrap/css/bootstrap-responsive.' . (lang_isrtl() ? 'rtl' : 'min') . '.css');
css::load(template_url() . '/lib/qtip2/jquery.qtip.min.css');
css::load(template_url() . '/css/blue.css');
css::load(template_url() . '/css/style' . (lang_isrtl() ? '_rtl' : null) . '.css');
$sitename = setting::get_data('site_name', 'val');

$loginLink = isset($_GET['forget']) || isset($_GET['confirm']) ? url::itself()->url_nonqry() : null;
?>   
<!DOCTYPE html>
<html lang="en" class="login_page">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?= !empty($sitename) ? $sitename : 'ArcadeBooster' ?> Admin</title>
        <?php
        if (isLocalServer()):
            css::load(template_url() . '/css/fonts/pt-sanse/font.css', array(CSS_FORCELOAD => true));
        else:
            ?>
            <link rel = "stylesheet" href = "http://fonts.googleapis.com/css?family=PT+Sans">
        <?php endif; ?>
        <base href="<?= template_url() ?>/"/>
        <link href="img/favicon.png" rel="shortcut icon" type="image/x-icon" />

        <!--[if lte IE 8]> 
            <link rel="stylesheet" href="<?= template_url() ?>/css/ie.css" />
            <script src="<?= template_url() ?>/js/ie/html5.js"></script>
            <script src="<?= template_url() ?>/js/ie/respond.min.js"></script> 
        <![endif]-->
        <style>
            .top_b{
                background: #f7db76 !important; /* Old browsers */
                background: -moz-linear-gradient(top,  #f7db76 0%, #f9bc2c 99%) !important; /* FF3.6+ */
                background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#f7db76), color-stop(99%,#f9bc2c)) !important; /* Chrome,Safari4+ */
                background: -webkit-linear-gradient(top,  #f7db76 0%,#f9bc2c 99%) !important; /* Chrome10+,Safari5.1+ */
                background: -o-linear-gradient(top,  #f7db76 0%,#f9bc2c 99%) !important; /* Opera 11.10+ */
                background: -ms-linear-gradient(top,  #f7db76 0%,#f9bc2c 99%) !important; /* IE10+ */
                background: linear-gradient(to bottom,  #f7db76 0%,#f9bc2c 99%) !important; /* W3C */
                filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f7db76', endColorstr='#f9bc2c',GradientType=0 ) !important; /* IE6-9 */

            }
        </style>
    </head>
    <body>
        <div class="login_box" style="opacity:0">

            <form action="<?= url::itself()->fulluri(array('forget' => null)) ?>" method="post" id="login_form">
                <input type="hidden"  name="submit" value="submit" /> 
                <div class="top_b" style="padding:0 10px 0 0; ">
                    <img src="img/logo-icon.png" style="float: left; height: auto;margin: 1px 11px 0 8px;"/>
                    <div style="float: left;width: 291px;height: 43px;overflow: hidden;"><?= L::global_sign_in_to; ?>&nbsp;<?= !empty($sitename) ? str::summarize($sitename, 30) : 'ArcadeBooster' ?> - <?= L::header_administration_area; ?></div>
                </div>    

                <div style="padding:10px 10px 0 10px;">
                    <?= alert('OnLoginAlert')->options(array(ALERT_OP_PADDING => 0)) ?> 
                </div>
                <div class="cnt_b">
                    <div class="formRow">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-user"></i></span><input type="text" id="username" name="username" placeholder="<?= addslashes(L::global_username);?>" />
                        </div>
                    </div>
                    <div class="formRow">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-lock"></i></span><input type="password" id="password" name="password" placeholder="<?= addslashes(L::global_password);?>" />
                        </div>
                    </div>
                    <div class="formRow clearfix">
                        <label class="checkbox"><input type="checkbox" name="remember_me" value="true"  /><?= L::global_remember_me; ?></label>
                    </div>
                </div>
                <div class="btm_b clearfix">
                    <button class="btn btn-inverse pull-right" type="submit"><?= L::global_sign_in; ?></button> 
                </div>  
            </form>

            <form action="<?= url::itself()->fulluri(array('forget' => 1)) ?>" method="post" id="pass_form" style="display:none">
                <input type="hidden"  name="submit" value="<?= addslashes(L::global_submit);?>" />
                <div class="top_b"><?= L::global_cant_signin; ?></div>    
                <div style="padding:10px 10px 0 10px;">
                    <?= alert('OnForgetAlert')->options(array(ALERT_OP_PADDING => 0)) ?> 
                </div>
                <div class="cnt_b">
                    <div class="formRow clearfix">
                        <div class="input-prepend">
                            <span class="add-on"><i class="icon-envelope"></i></span><input type="text" name="email" placeholder="<?= addslashes(L::global_your_email);?>" />
                        </div>
                    </div>
                </div>
                <div class="btm_b tac">
                    <button class="btn btn-inverse" type="submit"><?= L::global_request_new_pwd; ?></button>
                </div>  
            </form>

            <div id="confirm_form" style="display:none"> 
                <div class="top_b"><?= L::global_password_recovery; ?></div>    
                <div style="padding:10px 10px 0 10px;;min-height: 50px">
                    <?= alert('OnConfirmAlert')->options(array(ALERT_OP_PADDING => 0)) ?> 
                </div>  
                <div class="btm_b tac">
                    <a class="btn btn btn-inverse" href="<?= $loginLink ?>"><?= L::global_login ?></a>
                </div>  
            </div>

            <div class="links_b links_btm clearfix">
                <span class="linkform confrimF" style="display: none"><a href="#confirm_form">.</a></span>
                <span class="linkform forgetF"><a href="#pass_form"><?= L::global_forgot_pass ?></a></span>
                <span class="linkform loginF" style="display:none"><a href="<?= $loginLink ?>#login_form"><?= L::global_request_new_pwd; ?></a></span>
            </div>
        </div>
        <?php
        js::loadJquery(true);
        js::loadjquery_migrate(true);
        js::load(static_path() . '/js/jquery.actual.min.js', array(JS_FORCELOAD => true));
        js::load(template_path() . '/bootstrap/js/bootstrap.min.js', array(JS_FORCELOAD => true));
        js::load(template_path() . '/lib/qtip2/jquery.qtip.min.js', array(JS_FORCELOAD => true));
        ?>  
        <script type="text/javascript">

            $(document).ready(function() {
                //* boxes animation
                form_wrapper = $('.login_box');
                form_wrapper.css({marginTop: (-(form_wrapper.height() / 2) - 24)});
                form_wrapper.css({opacity: 100});
                $('.linkform a').on('click', function(e) {
                    var target = $(this).attr('href');
                    var target_height = $(target).actual('height');
                    $(form_wrapper).css({
                        'height': form_wrapper.height()
                    });
                    $(form_wrapper.find('form:visible')).fadeOut(400, function() {
                        form_wrapper.stop().animate({
                            height: target_height,
                            marginTop: (-(target_height / 2) - 24),
                            opacity: 1
                        }, 500, function() {
                            $(target).fadeIn(400);
                            $('.links_btm .linkform').toggle();
                            $(form_wrapper).css({
                                'height': ''
                            });
                        });
                    });
                    e.preventDefault();
                });
<?php
if (isset($_GET['forget']))
    echo "$('.forgetF a').first().trigger('click');";
elseif (isset($_GET['confirm']))
    echo "$('.confrimF a').first().trigger('click');";
?>
            });

        </script>

    </body>
</html>
