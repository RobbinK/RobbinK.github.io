<?php
get_header();
?>
<script type="text/javascript">
    $(document).ready(function() {

        $('#fld_username').bind({
            focus: function() {
                if ($(this).val() == '<?=L::forms_username;?>')
                    $(this).val('');
            }
            ,
            blur: function() {
                if ($(this).val() == '')
                    $(this).val('<?=L::forms_username;?>');
            }
        });

        $('#fld_pwd').bind({
            focus: function() {
                if ($(this).val() == '<?=L::forms_password;?>')
                    $(this).val('');
            }
            ,
            blur: function() {
                if ($(this).val() == '')
                    $(this).val('<?=L::forms_password;?>');
            }
        });
    });
</script>
<div id="content">
    <div class="single_box_outer_most_game">
        <center>
            <div class="box1_wrap_2">
                <div class="box1_header_2"><?=L::menu_login;?></div>
                <div class="page_container"> 

                    <?= alert() ?>
                    <form action="<?= url::itself()->fulluri(array('at' => 1)) ?>" method="post" > 
                        <div class="user_lable"></div><!--user_lable-->
                        <input type="text"  id="fld_username"  name="fld_username" value="<?=L::forms_username;?>"/>
                        <br>
                        <div class="pass_lable"></div><!--pass_lable-->
                        <input type="Password"  id="fld_pwd"  name="fld_pwd" value="<?=L::forms_password;?>"/>
                        <br>
                        <input type="checkbox" name="remember_me" value="1" /><lable><?=L::forms_remember_me;?></lable>
                        <br class="clear"/>
                        <div class="links_login">

                            <input type="button" onclick="this.form.submit()" value="<?=L::menu_login;?>" class="btn1 orange"/> 
                            <a href="<?= ab_router('usersignup'); ?>"><?=L::menu_signup;?></a>

                        </div><!--links_login-->
                    </form>

                </div>
            </div>
        </center>
    </div>
</div>
<?php
get_footer()
?>

