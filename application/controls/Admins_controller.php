<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admins_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class AdminsController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
    }

    function login() {
        $this->MapViewFileName('loginform.php');
        if (isset($_GET['forget'])) {
            $this->forget();
            return;
        }

        if (isset($_GET['confirm'])) {
            $model = new Admin;
            if ($model->confirmForget($_GET['confirm']))
                psuccess(L::alert_password_changed.'<br>'.L::alert_login_to_your_account)->Id('OnConfirmAlert');
            else
                warning(L::alert_error_occured)->Id('OnConfirmAlert');
        }

        $error = 0;
        if (file_exists(ROOT_PATH . '/install') && !DEVELOP) {
            perror(L::alert_del_install_folder)->Id('OnLoginAlert');
            $error = 1;
        }
        $error = 0;
        if (file_exists(ROOT_PATH . '/ab_toolkits') && !DEVELOP) {
            perror(L::alert_del_abtools_folder)->Id('OnLoginAlert');
            $error = 1;
        }

        $model = new Admin;
        if (!$error && isset($_POST['submit'])) {
            $remember = false;
            if (isset($_POST['remember_me']))
                $remember = convert::to_bool($_POST['remember_me']);

            $model->setLoginPage(url::router('admindashboard'));
            $checklogin = $model->dologin($_POST['username'], $_POST['password'], $remember);
            if ($checklogin === LOGIN_USER_NOTACTIVE)
                perror(L::alert_account_deactive)->Id('OnLoginAlert');
            if ($checklogin === LOGIN_USER_WRONG)
                perror(L::alert_invalid_user_pass)->Id('OnLoginAlert');
            ref(url::itself()->fulluri())->redirect();
        }

        $model->setLoginPage(url::router('admindashboard'));
        $model->CheckLogin();
        $model->discard();
    }

    function logout() {
        global $router;
        $model = new admin();
        $model->setLogoutPage(url::router('adminlogin'))->logout();
    }

    function forget() {
        if (isset($_POST['submit'])) {
            $model = new Admin;
            $res = $model->forget($_POST['email']);
            if ($res === LOGIN_FORGET_SENT){
                psuccess(L::alert_password_sent)->Id('OnForgetAlert')->priority(1);
                psuccess(L::alert_check_junk)->Id('OnForgetAlert')->priority(2);
            }
            else if ($res === LOGIN_FORGET_ERROR_OCCURS)
                perror(L::alert_invalid_email)->Id('OnForgetAlert');
            else
                warning(L::alert_error_occured)->Id('OnForgetAlert');
            ref(url::itself()->fulluri(array('forget' => 1)))->redirect();
        }
    }

    function changepass() {
        $this->islogin();
        $model = new Admin;
        if (isset($_POST['submit'])) {
            $change = $model->ChangePass($model->data->id, $_POST['newpassword'], $_POST['oldpassword']);
            if ($change == 1)
                psuccess(L::alert_password_changed);
            else if ($change == 0)
                perror(L::alert_invalid_old_password);
            ref(url::itself()->fulluri())->redirect();
        }
    }

    function changeto_premium() {
        $this->MapViewFileName('notprimume.php');
    }

}