<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_includecashe_funcs.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function _include($file, $name = null, $cachetime) {
    $cache = new Caching();
    $cache->sCacheDir = cache_path() . '/';
    $cache->sCacheURL = cache_url() . '/';

    $cachname = str_replace(ROOT_PATH, null, $file);
    $cachname = str_replace(array('/', '\\'), '___', $cachname);

    if (!empty($name))
        $cachname.='(' . strtolower($name) . ')';
    else
        $cachname.='(' . str_replace(array('/', '\\'), '___', strtolower($_SERVER['REQUEST_URI'])) . ')';

    ob_start();
    if (!$cache->getCache($cachname, $cachetime)) {
        eval(globals_st($GLOBALS));
        include($file);
        $cache->saveCache();
    }
    $content = ob_get_contents();
    ob_end_clean();
    echo $content;
}