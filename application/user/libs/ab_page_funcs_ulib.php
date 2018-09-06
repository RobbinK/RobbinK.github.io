<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_page_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function ab_static_pages() {
    $page = new Page; 
    $page->PagesList();
    return $page;
}

function ab_page($page_seo_title) {
    //checked
    $page = new Page; 
    $res = $page->showpageBySeo($page_seo_title);
    return $res;
}
function ab_page_byid($page_id) {
    //checked
    $page = new Page; 
    $res = $page->showpageById($page_id);
    return $res;
}