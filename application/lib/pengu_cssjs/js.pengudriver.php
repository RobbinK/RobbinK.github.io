<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: js.pengudriver.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


@ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);
##############################################
require_once '../../../path.php';
require_once (ROOT_PATH . '/core/lib/lib.php');
require_once (ROOT_PATH . '/core/lib/base64.class.php');
require_once (ROOT_PATH . '/core/lib/pengu_tmp.class.php');
require_once (ROOT_PATH . '/core/lib/pengu_setting.class.php');
############################################## 
if (empty($_GET['tkey']))
    exit;

header("Content-type: text/javascript; charset= UTF-8");
//=== GetTargetSetting 
$setting = new pengu_setting(ROOT_PATH . '/tmp/etc');
$setting->setSettingPrefix('cssjs_');
$setting->setSettingName($_GET['tkey']);
$data = $setting->get('targets');
if (empty($data))
    exit;
if (!is_array($data))
    $fileTarget[] = $data;
else
    $fileTarget = $data;
unset($data);
#----------------------
// RunCache
#----------------------   
$ServerCacheTime = @intval($_GET['scache']) ? intval($_GET['scache']) : 256 * 24 * 3600;
if (!$ServerCacheTime) {
    if (is_array($fileTarget)) {
        foreach ($fileTarget as $f)
            include_once($f);
    }
    exit;
}



$cache = new pengu_cache(ROOT_PATH . '/tmp/cache/etc/', 'cache_', '.js');
$cache->expireTime($ServerCacheTime);

$content = null;
$mtime = array();
foreach ($fileTarget as $f) {

    $cache->setCacheKey(md5($f));
    $cache->sensetiveFileTrigger($f);

    if (!$cache->isCached()) {
        /* if Not cached */

        require_once (ROOT_PATH . '/application/lib/pengu_cssjs/incs.php');
        require_once (ROOT_PATH . '/application/lib/pengu_cssjs/lib/packer/class.JavaScriptPacker.php');

        if (!isset($conf))
            $conf = base64::decode($_GET['config']);
        if (!empty($_GET['skey']) && !isset($settingD)) {
            $settingD = new pengu_setting(ROOT_PATH . '/tmp/etc');
            $settingD->setSettingPrefix('direction_');
            $settingD->setSettingName($_GET['skey']);
            direction::import($settingD->get('siteinfo'));
        }

        ob_start();
        include_once($f);
        $data = ob_get_clean();

        /* encrypt */
        if (!preg_match('/(\.packed|\-packed|\-min|\.min)\.js/', $f) && $conf['minify'] === true) {
            $packer = new JavaScriptPacker($data, 'Normal', true, false);
            $data = $packer->pack();
        }
        if (!preg_match('/\;[\s\r\n\t]*$/', $data))
            $data .= ';';


        $cache->write($data);
        $content.=$data;
        $mtime[] = time();
        unset($data);
    } else {
        /* If Cached */
        $content.= $cache->read();
        $mtime[] = filemtime($cache->getCachePath());
    }
}
include_once ROOT_PATH . '/application/lib/pengu_cssjs/lib/lib.php';
enable_cache_headers(max($mtime), $cache->getCacheKey());
//--enable GZ
if (isset($_SERVER['HTTP_ACCEPT_ENCODING']) && @substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
    header('Content-Encoding: gzip');
    @ob_start("ob_gzhandler");
}
//--
echo $content;