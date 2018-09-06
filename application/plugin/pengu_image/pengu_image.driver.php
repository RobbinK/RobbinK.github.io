<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: pengu_image.driver.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


@ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

$root = base64_decode(urldecode($_GET['root']));


#----------------------
// join with framework
#----------------------
##############################################
require_once ($root . '/path.php');
require_once (ROOT_PATH . '/core/lib/base64.class.php');
require_once (ROOT_PATH . '/application/lib/lib.php');

##############################################
@date_default_timezone_set('UTC');
header("Cache-Control: private, max-age=10800, pre-check=10800");
header("Pragma: private");
header("Expires: " . @date(DATE_RFC822, @strtotime(" 2 day")));

$imgsrc = base64::decode($_GET['img']);
//$mime = path::get_mime_type($imgsrc);
if (@substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
    ob_start("ob_gzhandler");
header("Content-Type: image/jpg");

function enable_cache_headers($file = null, $etagFile) {
    $ClientCache = (isset($_GET['ccache']) && $_GET['ccache'] == 0 ? false : true);

    if ($ClientCache) {
        $lastModified = filemtime($file);
        $ifModifiedSince = (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) ? @strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) : false);
        $etagHeader = (isset($_SERVER['HTTP_IF_NONE_MATCH']) ? trim($_SERVER['HTTP_IF_NONE_MATCH']) : false);

        header("Last-Modified: " . gmdate("D, d M Y H:i:s", $lastModified) . " GMT");
        header("Etag: $etagFile");
        header('Cache-Control: public');

        if ($ifModifiedSince == $lastModified || $etagHeader == $etagFile) {
            header("HTTP/1.1 304 Not Modified");
            exit();
        }
    } else {
        /* no Cache */
        header('Cache-control: public');
        header("Cache-Control: no-cache, must-revalidate"); //HTTP 1.1
        header("Pragma: no-cache"); //HTTP 1.0
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past 
    }
}

enable_cache_headers($imgsrc, md5($imgsrc));

$image = imagecreatefromjpeg($imgsrc);
imagejpeg($image);
imagedestroy($image);

