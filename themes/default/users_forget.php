<?php
get_header();
?> 
<script type="text/javascript">
    $(function() {
<?php if (isset($_GET['confirm'])): ?>
            $('#confirm_form').show();
<?php else: ?>
            $('#recovery_form ').show();
<?php endif; ?>
    });
</script>
<div id="content">
    <div class="single_box_outer_most_game">
        <center>
            <div class="box1_wrap_2">
                <div class="box1_header_2"><?=L::forms_pwd_recovery;?></div>
                <div class="page_container"> 

                    <div id="confirm_form" style="display:none">      
                        <?= alert('OnConfirmAlert')->options(array(ALERT_OP_PADDING => 10)) ?>  
                        <div class="btm_b tac center">
                            <a class="btn1 blue" style="margin-left: 0px" href="<?= ab_router('userlogin') ?>"><?=L::menu_login;?></a>
                        </div>  
                    </div> 

                    <div id="recovery_form" style="display: none"> 
                        <?= alert('OnForgetAlert')->options(array(ALERT_OP_PADDING => 10)) ?>  
                        <form  id="recovery" action="<?= url::itself() ?>" method="post"> 
                            <input type="hidden"  name="submit" value="submit" /> 
                            <?=L::forms_email;?>:  
                            <input type="text"  id="email"  name="email" /> 
                            <br/> 
                            <button class="btn1 orange" onclick="$('#recovery').submit();"><?=L::forms_submit;?></button>                             
                        </form>
                    </div>
                </div>
            </div>
        </center>
    </div>
</div>
<?php
get_footer()
?>