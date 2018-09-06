<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: forbidden.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:54
##########################################################
 */

### call header
abs_admin_inc(l_basic);
get_header();
#**************
?>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Jockey+One" />
<!-- main content --> 
<div class="error_page" style="height: 600px;position: absolute;top:0;left: 0;width: 100%;height: 100%;">   

    <div class="error_box" style="margin-left: 25%">
        <br>
        <h1><?=L::alert_permission_denied;?></h1>
        <p><?=L::alert_no_access_message;?></p>
        <a href="javascript:history.back()" class="back_link btn btn-small"><?=L::global_back;?></a>
    </div>

</div>

<?php
get_sidebar();
get_footer('_script');
get_footer();
?>