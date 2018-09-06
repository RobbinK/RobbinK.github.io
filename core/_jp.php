<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _jp.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


@ini_set('display_errors', 'On');
error_reporting(E_ALL ^ E_NOTICE);

//need include path.php
############################################## 
require_once(ROOT_PATH . '/core/base/sysvars.php');
require_once(ROOT_PATH . CONFIG_DIR . '/site.config.php');
require_once(ROOT_PATH . '/core/lib/base64.class.php');
require_once(ROOT_PATH . '/core/lib/microtimer.class.php');
require_once(ROOT_PATH . '/core/lib/aes.crypt.class.php');
require_once(ROOT_PATH . '/core/lib/pengu_log.class.php');
require_once (ROOT_PATH . '/core/lib/pengu_tmp.class.php');
require_once (ROOT_PATH . '/core/lib/pengu_setting.class.php');
require_once(ROOT_PATH . '/core/lib/register.class.php');
require_once (ROOT_PATH . '/application/lib/url.class.php');
require_once (ROOT_PATH . '/application/lib/path.class.php');
require_once (ROOT_PATH . '/application/plugin/pengu_image/pengu_image.class.php');
require_once (ROOT_PATH . '/application/lib/convert.class.php');

require_once (ROOT_PATH . '/core/base/router/booter.php');

require_once (ROOT_PATH . '/core/base/direction.class.php');
require_once (ROOT_PATH . '/core/lib/userdefines.lib.php');

require_once(ROOT_PATH . CONFIG_DIR . '/db.config.php');
require_once(ROOT_PATH . '/core/base/db/booter.php');
require_once(ROOT_PATH . '/core/base/inflector.class.php');
require_once(ROOT_PATH . '/core/base/model.base.class.php');
############################################## 
$PenguInflector = new Inflector;
############################################## 
require_once(ROOT_PATH . '/application/lib/input.class.php');
require_once(ROOT_PATH . '/application/lib/array.class.php');
require_once(ROOT_PATH . '/application/lib/str.class.php');
require_once(ROOT_PATH . '/application/lib/validate.class.php');
require_once(ROOT_PATH . '/application/lib/convert.class.php');
require_once(ROOT_PATH . '/application/lib/lib.php');
if (DEVELOP)
    require_once(ROOT_PATH . '/application/lib/dump.class/dBug.php');
require_once(ROOT_PATH . '/application/lib/agent/agent.class.php');
require_once(ROOT_PATH . '/application/lib/date.class/pengu.date.class.php');
require_once(ROOT_PATH . '/application/lib/url.class.php');
require_once(ROOT_PATH . '/application/lib/ref.class/ref.class.php');

require_once(ROOT_PATH . '/application/plugin/pengu_message/message.class.php');
########################################################

if (isset($_GET['skey']) && !empty($_GET['skey'])) {
    $setting = new pengu_setting(ROOT_PATH . '/tmp/etc');
    $setting->setSettingPrefix('direction_');
    $setting->setSettingName($_GET['skey']);
    $pathdata = $setting->get('siteinfo');
    direction::import($pathdata);
}