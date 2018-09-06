<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: _ptrace.cookie.class.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

event::register_onShowedView(array('_ptrace_cookie','trace'));
class _ptrace_cookie extends _ptrace {

    function trace() {
        $deny = array(
            'XDEBUG_SESSION',
            'PHPSESSID',
            'ptrace_.*'
        );
        $pattern = '/' . join('|', $deny) . '/i';


        $data = array();
        foreach ($_COOKIE as $ckey=>$cval) {
            if (!preg_match($pattern, $ckey))
                $data[$ckey] = $cval; 
        }
        self::savetofile('ptrace.cookie', $data); 
    } 

}