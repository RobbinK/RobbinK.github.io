<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: lib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */

@date_default_timezone_set("Europe/London");
function enable_cache_headers($lastModified = null,$etagFile) {
    $ClientCache = (isset($_GET['ccache']) && $_GET['ccache'] == 0 ? false : true);

    if ($ClientCache) {  
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        header("Last-Modified: " . @gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etagFile");
        header('Cache-Control: public');

        if ($ifModifiedSince == $lastModified && $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            exit;
        }
    } else {
        /* no Cache */
        header('Cache-control: public');
        header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
        header("Pragma: no-cache"); //HTTP 1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
    }
}