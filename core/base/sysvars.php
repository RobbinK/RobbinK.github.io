<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: sysvars.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

define('PENGU_ERROR_STATUS', E_ALL | E_STRICT);
error_reporting(PENGU_ERROR_STATUS);
if (isset($_GET['aberror']))
    $_SESSION['aberror'] = $_GET['aberror'];
if (isset($_SESSION['aberror'])) {
    if ($_SESSION['aberror'] >= 1)
        ini_set('display_errors', 1);
    if ($_SESSION['aberror'] == 2)
        define('DEVELOP', 1);
    else
        define('DEVELOP', 0);
}else {
    @ini_set('display_errors', 0);
    define('DEVELOP', 0);
}
define('sys_ver', '1.5.7.4');
define('master_url', 'http://www.arcadebooster.com');
define('master_domain', 'arcadebooster.com');