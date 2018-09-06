<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_member_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function ab_latest_members($limit = null) {
    $mem =new Member;
    $mem->Allmembers($limit);
    return $mem;
}