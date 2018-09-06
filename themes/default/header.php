<?php
css::loadAlert();
css::load(array(
    template_url() . '/css/styles.css',
    template_url() . '/css/ratestar.css',
    template_url() . '/css/pagination.css',
    template_url() . '/js/jRating/css/jRating.jquery.css',
));
js_header_libraries();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta content="text/html;charset=utf-8" http-equiv="Content-Type"/>
        <title><?= ab_page_title() ?></title>
        <meta name="description" content="<?= ab_meta_description() ?>" />
        <meta name="keywords" content="<?= ab_meta_keywords() ?>" /> 
        <link href="<?= ab_template_images() ?>/favicon.png" rel="shortcut icon" type="image/x-icon" /> 
        <?= ab_canonical() ?>
        <script type="text/javascript">
            var plugin_url = "<?= plugin_url() ?>";
            var ajaxurl = "<?= ab_router('ajaxgate'); ?>";
            var addtofavUrl = '<?= ab_router('useraddtofavorit') ?>';

            $(document).ready(function () {
                $('#fld_username2,#fld_username').bind({
                    focus: function () {
                        if ($(this).val() == '<?= L::menu_username; ?>')
                            $(this).val('');
                    }
                    ,
                    blur: function () {
                        if ($(this).val() == '')
                            $(this).val('<?= L::menu_username; ?>');
                    }
                });

                $('#fld_pwd2,#fld_pwd').bind({
                    focus: function () {
                        if ($(this).val() == '<?= L::menu_password; ?>')
                            $(this).val('');
                    }
                    ,
                    blur: function () {
                        if ($(this).val() == '')
                            $(this).val('<?= L::menu_password; ?>');
                    }
                });
                $('.lable_sign_in').click(function (e) {
                    $('.login_box').slideToggle();
                    e.stopPropagation();
                });
                $('.login_box').click(function (e) {
                    e.stopPropagation();
                });
                $(document).click(function () {
                    $('.login_box').slideUp();
                });

                /*--jratting--*/
                if ($(".reteBox").length)
                    $(".reteBox").jRating({
                        rateMax: 100,
                        length: 5,
                        smallStarsPath: '<?= template_url() . '/js/jRating/icon/small.png' ?>',
                        type: 'small',
                        isDisabled: true
                    });
            });


        </script>

    </head>
    <body>
        <div id="wrapper">
            <div id="container">
                <div class="top_menu">
                    <div class="box_menu">
                        <a href="<?= ab_router('newgames') ?>"><?= L::menu_new_games; ?></a>
                        <a href="<?= ab_router('populargames') ?>"><?= L::menu_popular_games; ?></a>
                        <a href="<?= ab_router('toprategames') ?>"><?= L::menu_top_rated_games; ?></a>
                        <a href="<?= ab_router('usersubmission') ?>"><?= L::menu_submit_your_games; ?></a>
                        <a href="<?= ab_router('usercontact') ?>"><?= L::menu_contact_us; ?></a>
                        <a href="<?= ab_router('links') ?>"><?= L::forms_partners_links; ?></a>
                    </div><!--box_menu-->
                    <div class="sign_in">
                        <div class="r5">
                            <?php
                            if (Member::isLogin()) {
                                ?>
                                <div class="lable_sign_in">Account</div>
                                <div class="login_box r5b">
                                    <div class="left space" style="width: 100%;"><?= L::forms_welcome; ?> <i><?= Member::data('name') ?></i></div>

                                    <a href="<?= ab_router('userfavorites') ?>" style="color: #000;"> <?= L::menu_favorites; ?> </a>
                                    <a href="<?= ab_router('userprofile') ?>" style="color: #000;"><?= L::menu_profile; ?></a>
                                    <a href="<?= ab_router('userlogout') ?>" style="color: #000;"><?= L::menu_logout; ?></a> 
                                </div>
                                <?php
                            } else {
                                ?> 
                                <div class="lable_sign_in">
                                    <i></i>
                                    <?= L::menu_login; ?></div>
                                <a href="<?= ab_router('usersignup'); ?>">
                                    <div class="lable_sign_up"><?= L::menu_signup; ?>
                                        <i></i>
                                    </div></a>
                            </div>
                            <div class="login_box r5b">


                                <form action="<?= ab_router('userlogin') ?>&at&alertid=loginerror" method="post" >
                                    <input type="text"  id="fld_username2"  name="fld_username" value="<?= L::menu_username; ?>" />        
                                    <br/>
                                    <input type="Password"  id="fld_pwd2"  name="fld_pwd" value="<?= L::menu_password; ?>" />
                                    <input type="button" onclick="this.form.submit()" value="<?= L::menu_login; ?>" id="loginbtn"/> 
                                    <br class="clear"/>
                                    <lable> <?= L::menu_remember_me; ?> </lable><input type="checkbox" name="remember_me" value="1" style="margin-top:6px; float: left;"/>
                                    <a href="<?= ab_router('userforget') ?>" style="margin-left: 0px;"><?= L::menu_forgot_pass; ?></a>
                                </form>
                                <?php
                            }
                            ?>
                        </div><!--login_box-->
                        <script type="text/javascript">
                            var msg = '<?= alert('loginerror')->options(array(ALERT_OP_HTMLTAG => false)); ?>';
                            if (msg != '')
                                alert(msg);
                        </script>
                    </div><!--sign_in-->
                </div><!--top_menu-->

                <div id="header"> 
                    <div class="top_header">
                        <div id="logo">
                            <a href="<?= ab_router('homepage') ?>"><img src="<?= ab_template_images() ?>/logo.png" /></a>
                        </div>
                        <div id="headerads"> 
                            <!----ads(468x60)---->
                            <?= ab_show_ad('468x60-small-rectangle') ?>
                        </div>


                        <div class="search_box">
                            <form action="<?= ab_router('dosearch') ?>" method="post" >
                                <div class="left space">
                                    <input type="text"  id="fld_search"  name="search" value="<?= L::menu_search_games; ?>" onfocus="if (this.value == '<?= L::menu_search_games; ?>') {
                                                this.value = '';
                                            }" onblur="if (this.value == '') {
                                                        this.value = '<?= L::menu_search_games; ?>';
                                                    }"/>
                                </div>
                                <div class="left space">
                                    <input type="submit" value=""  id="searchbtn"/>
                                </div>
                            </form>
                        </div><!--search_box-->

                        <br style="clear: both"/>
                        <div class="menu">
                            <ul>
                                <li><a href="<?= ab_router('homepage') ?>"><?= L::menu_home; ?></a></li>
                                <?= ab_show_categories() ?>
                            </ul>
                        </div><!--menu-->
                    </div><!--top_header-->
                </div>
