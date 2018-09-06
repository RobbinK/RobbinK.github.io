<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: path.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


define('DEFAUT_THEMES_DIR', '/themes');
define('CONFIG_DIR', '/config');

//Directory & URL Separator  
define('DS', '/');
define('US', '/');

function get_root() {
    if (isset($_SERVER['REAL_DOCUMENT_ROOT']))
        return rtrim(str_replace('\\', DS, realpath($_SERVER['REAL_DOCUMENT_ROOT'])), DS);
    else
        return rtrim(str_replace('\\', DS, realpath(@$_SERVER['DOCUMENT_ROOT'])), DS);
}

define('HOST_PATH', get_root());
define('ROOT_PATH', str_replace('\\', DS, dirname(realpath(__FILE__))));

require_once(ROOT_PATH . CONFIG_DIR . '/site.config.php');
require_once(ROOT_PATH . '/core/lib/lib.php');

if (!defined('FileSubDir'))
    define('FileSubDir', str_replace(HOST_PATH, null, ROOT_PATH));
define('HOST_NAME', get_host());
define('HOST_URL', SiteProtocol . HOST_NAME);
define('ROOT_URL', HOST_URL . FileSubDir);