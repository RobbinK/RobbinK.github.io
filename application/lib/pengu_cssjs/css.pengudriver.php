<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: css.pengudriver.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


@ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
$compress = true;
##############################################
require_once '../../../path.php';
require_once (ROOT_PATH . '/core/lib/lib.php');
require_once (ROOT_PATH . '/core/lib/pengu_tmp.class.php');
require_once (ROOT_PATH . '/core/lib/pengu_setting.class.php');

##############################################  
header("Content-type: text/css; charset= UTF-8");

if ($p = strpos($_SERVER['REQUEST_URI'], '?'))
    $fileTarget = substr($_SERVER['REQUEST_URI'], 0, $p);
else
    $fileTarget = $_SERVER['REQUEST_URI'];
$fileTarget = preg_replace('/^(.*\.css)\.min$/i', '$1', $fileTarget);
$fileFullPath = HOST_PATH . $fileTarget;
if (!file_exists($fileFullPath))
    exit;

#----------------------
// RunCache
#---------------------- 

$ServerCacheTime = @intval($_GET['scache']) ? intval($_GET['scache']) : 256 * 24 * 3600;
if (!$ServerCacheTime) {
    include_once(HOST_PATH . $fileTarget);
    exit;
}
$cache = new pengu_cache(ROOT_PATH . '/tmp/cache/etc/', 'cache_', '.css');
$cache->expireTime($ServerCacheTime);
$cache->setCacheKey(md5($fileTarget));
$cache->sensetiveFileTrigger($fileFullPath);

$content = null;
if (!$cache->isCached()) {

    require_once (ROOT_PATH . '/application/lib/pengu_cssjs/incs.php');
    require_once (ROOT_PATH . '/application/lib/pengu_cssjs/lib/css-compressor.php');
    if (!empty($_GET['skey'])) {
        $setting = new pengu_setting(ROOT_PATH . '/tmp/etc');
        $setting->setSettingPrefix('direction_');
        $setting->setSettingName($_GET['skey']);
        $pathdata = $setting->get('siteinfo');
        direction::import($pathdata);
    }
    /* saving content */
    ob_start();
    @include($fileFullPath);
    $content = ob_get_clean();
    if ($compress)
        $content = compress($content);
    $cache->write($content);
    
    //--enable GZ
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && @substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
        header('Content-Encoding: gzip');
        @ob_start("ob_gzhandler");
    }
    //--
    echo $content;
    exit;
} else {
    include_once ROOT_PATH . '/application/lib/pengu_cssjs/lib/lib.php';
    enable_cache_headers(filemtime($cache->getCachePath()), $cache->getCacheKey());
    //--enable GZ
    if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && @substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
        header('Content-Encoding: gzip');
        @ob_start("ob_gzhandler");
    }
    //--
    echo $cache->read();
    exit;
}

