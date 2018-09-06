<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _ptrace.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


include('_ptrace.cookie.class.php');

class _ptrace {

    protected static function savetofile($name, $data) {
        $tmp = new pengu_setting;
        $tmp->setSettingName($name);
        $tmp->setSettingPath(ROOT_PATH . '/tmp/etc');
        $tmp->setSettingPrefix('ptrace_');
        $tmp->delete();
        $tmp->save($data);
    }

}