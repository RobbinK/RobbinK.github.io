<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin extends login {

    protected $_table = "abs_members";
    protected $_name = "absadminlogin";
    protected $LoginOptionField = array(
        'PK_Field' => 'id',
        'username_Field' => 'username',
        'password_Field' => 'password',
        'name_Field' => 'name',
        'email_Field' => 'email',
        'active_Filed' => array('status' => 1),
        'confirm_Field' => 'confirm'
    );

    function __construct() {
        parent::__construct();
        $this->RegisterEvent_OnUserLogin('userlogin');
        $this->RegisterEvent_OnUserLogout('userlogout');
    }

    protected function userlogin($userid) {
        $this->update(array(
            'login' => array('login+1'),
            'lastlogin' => time(),
            'ip_info_range' => agent::remote_info_ip(),
            'ip_info_country' => agent::remote_info_country_code()
        ))->where(array('id' => $userid))->limit(1)->exec();
    }

    protected function loginQuery($condition) {
        return $this->select()->where(array_merge($condition, array('`group` in (1,3,4)')))->limit(1)->exec();
    }

}