<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: index.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


if (!ini_get('short_open_tag')) {
    @ini_set('short_open_tag', 'On');
}
@ini_set("register_globals", 0);
require_once('path.php');
require_once(ROOT_PATH . '/core/_init.php');