<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _init.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


@session_start();
require_once(ROOT_PATH . '/core/base/sysvars.php');
require_once(ROOT_PATH . '/core/lib/register.class.php');
require_once(ROOT_PATH . '/core/lib/pengu_tmp.class.php');
require_once(ROOT_PATH . '/core/lib/pengu_setting.class.php');
require_once(ROOT_PATH . CONFIG_DIR . '/sys.config.php');
require_once(ROOT_PATH . '/core/base/router/booter.php');
######################################
// Set Path Class
require_once(ROOT_PATH . '/core/base/direction.class.php');
require_once(ROOT_PATH . '/core/lib/userdefines.lib.php');
direction::setDefaults();
######################################
global $router;
$route = $router->matchCurrentRequest();
if ($route) {
###################################### 
    require_once(ROOT_PATH . '/core/lib/microtimer.class.php');
    require_once(ROOT_PATH . '/core/lib/base64.class.php');
    require_once(ROOT_PATH . '/core/lib/aes.crypt.class.php');
    require_once(ROOT_PATH . '/core/lib/pengu_log.class.php');
    require_once(ROOT_PATH . CONFIG_DIR . '/db.config.php');
    require_once(ROOT_PATH . '/core/base/db/booter.php');
    require_once(ROOT_PATH . '/core/base/inc.lib.php');
    require_once(ROOT_PATH . '/core/base/event.class.php');
    require_once(ROOT_PATH . '/core/base/dispatcher.class.php');
    require_once(ROOT_PATH . '/core/base/inflector.class.php');
    require_once(ROOT_PATH . '/core/base/model.base.class.php');
    require_once(ROOT_PATH . '/core/base/control.base.class.php');
    require_once(ROOT_PATH . '/core/base/view.class.php');
######################################
    global $PenguInflector;
    $PenguInflector = new Inflector;
    new dispather($route->getTarget(), $route->getParameters());
    exit;
} else {
###################################### 
    require_once(ROOT_PATH . '/core/lib/microtimer.class.php');
    require_once(ROOT_PATH . '/core/lib/base64.class.php');
    require_once(ROOT_PATH . '/core/lib/aes.crypt.class.php');
    require_once(ROOT_PATH . '/core/lib/pengu_log.class.php');
    require_once(ROOT_PATH . CONFIG_DIR . '/db.config.php');
    require_once(ROOT_PATH . '/core/base/db/booter.php');
    require_once(ROOT_PATH . '/core/base/inc.lib.php');
    require_once(ROOT_PATH . '/core/base/event.class.php');
    require_once(ROOT_PATH . '/core/base/dispatcher.class.php');
    require_once(ROOT_PATH . '/core/base/inflector.class.php');
    require_once(ROOT_PATH . '/core/base/model.base.class.php');
    require_once(ROOT_PATH . '/core/base/control.base.class.php');
    require_once(ROOT_PATH . '/core/base/view.class.php');
######################################
    global $PenguInflector;
    $PenguInflector = new Inflector;
    $route = $router->getRoute('page404');
    if (!empty($route)) {
        new dispather($route->getTarget(), array());
        exit;
    } else {
        echo " <fieldset style='width: 400px;margin: 100px auto 0px auto; text-align: center;padding: 20px; color: #990000'>
        <strong>404.</strong> That's an error.
        <br/><br/>
        The requested URL  <b>{$_SERVER['REQUEST_URI'] }</b> was not found on this server.
    </fieldset>";
    }
}
