<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: booter.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


require_once('router.class.php');
require_once('route.class.php');
if (file_exists(ROOT_PATH . CONFIG_DIR . '/routes.custom.config.php'))
    require_once(ROOT_PATH . CONFIG_DIR . '/routes.custom.config.php');
require_once(ROOT_PATH . CONFIG_DIR . '/routes.config.php');