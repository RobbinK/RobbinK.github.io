<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: tinymce.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */
  
class tinymce {

    private static function loadtiny() {
        global $abs_admin_inc_jscontents;

        if (!isset($abs_admin_inc_jscontents)) {
            if (!js::loadedJquery())
                js::loadJquery();
        } 
        $tinysrc = plugin_path() . '/tinymce/tiny_mce_4.0.5/tinymce.gzip.js';

        if (!js::loaded($tinysrc)) {
            if (isset($abs_admin_inc_jscontents))
                abs_admin_inc_js($tinysrc);
            else
                js::load($tinysrc, array(JS_EXEC => false,JS_MINIFY=>false));
        }
    }

    public static function load($config_File = 'en1.config.js') {
        global $abs_admin_inc_jscontents;
        pengu_user_load_lib('ab_admin_cssjs_include');
        self::loadtiny(); 
        $configSrc = static_path() . '/config/tinymce/' . $config_File;
        if (!js::loaded($configSrc))
            if (isset($abs_admin_inc_jscontents))
                abs_admin_inc_js($configSrc, array(JS_EXEC => true,JS_MINIFY=>true));
            else
                js::load($configSrc);
    } 
}