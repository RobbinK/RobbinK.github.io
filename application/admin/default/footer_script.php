<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: footer_script.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:54
##########################################################
 */

echo abs_admin_place_js();
js::load(template_path() . '/js/abs_common.js', array(JS_FORCELOAD => true,JS_MINIFY=>false)); 
?>     

<script type="text/javascript">
    $(document).ready(function() {
        //* show all elements & remove preloader
        setTimeout('$("html").removeClass("js")', 1000);
    });
</script>
