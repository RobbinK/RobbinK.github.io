<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_configurations_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */


class Admin_configurationsController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_configuration');

        /* get comment from setting */

        function getcomment($data) {
            $result = array();
            if (!isset($data['comment_' . lang()]))
                    return false;
            $value = $data['comment_' . lang()];
            if (strpos($value, '#')) {
                $result['title'] = substr($value, 0, strpos($value, '#'));
                $result['text'] = substr($value, strpos($value, '#') + 1);
            }
            else
                $result['text'] = substr($value, 0);
            if (!empty($result['text']) || !empty($result['title']))
                return "<a class='pop_over' data-placement='right' data-original-title='" . @$result['title'] . "' data-content='" . @$result['text'] . "'>";

            return false;
        }

    }

    function seo() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function mainsetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }



                //Save Site.config
                $config = array();
                if (isset($_POST['main']['smtp_email_from']))
                    $config['SetEmailFrom'] = $_POST['main']['smtp_email_from'];

                if (isset($_POST['main']['smtp_email_from_name']))
                    $config['SetEmailName'] = $_POST['main']['smtp_email_from_name'];

                if (isset($_POST['main']['close_site']))
                    $config['CloseSiteForMaintenance'] = convert::to_bool($_POST['main']['close_site']);

                if (isset($_POST['main']['site_template']))
                    $config['DefaultTemplate'] = $_POST['main']['site_template'];

                if (isset($_POST['main']['site_protocol']))
                    $config['SiteProtocol'] = $_POST['main']['site_protocol'];

                if (!$this->saveConfigFile($config)) {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = 'the /config/site.config.php file is not writeable!<br> set 777 permission for it. ';
                    echo json_encode($json_out);
                    exit;
                }

                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
        $themes = path::getFolderList(root_path() . '/themes');
        $this->set('themes', $themes);
    }

    function commentsetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function gamesubmission() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function membersetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

//                $arr = array();
//                foreach ($_POST as $k => $v) {
//                    if ($k == 'Members__Avatar_Dimentions_width') {
//                        $arr['width'] = $v;
//                        unset($_POST[$k]);
//                    } elseif ($k == 'Members__Avatar_Dimentions_height') {
//                        $arr['height'] = $v;
//                        unset($_POST[$k]);
//                    } else {
//                        $cat = substr($k, 0, strpos($k, '__'));
//                        $key = substr($k, strpos($k, '__') + 2);
//                        $_POST[$cat][$cat . '_' . $key] = $v;
//                    }
//                }
//                if (!empty($arr['width']) && !empty($arr['height'])) {
//                    $_POST['members']['members_avatar_dimentions'] = join('x', $arr);
//                }

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function cachesetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $_POST['cache']['cache_time']*=60;

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
//Save Site.config
                $config = array();

                if (isset($_POST['cache']['cache']))
                    $config['UseCache'] = convert::to_bool($_POST['cache']['cache']);

                if (isset($_POST['cache']['cache_time']))
                    $config['CacheExpireTime'] = convert::to_integer($_POST['cache']['cache_time']);

                $this->saveConfigFile($config);
///
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function sitemapsetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function arcadeboostersetting() {

        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }

                echo json_encode($json_out);
                exit;
            }
        }
    }

    function feedsetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function scriptsetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function linkexchange() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    function tradesetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);


                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                }
///
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    private function saveConfigFile(array $data) {
        global $_data;
        $_data = $data;
        if (!_dbaffecting())
            return false;
        if (!function_exists('_ss')) {

            function _ss($def_name) {
                global $_data;
                $res = 'null';
                if (isset($_data[$def_name])) {
                    $res = $_data[$def_name];
                } else if (defined($def_name))
                    $res = eval("return {$def_name};");

                if (is_bool($res))
                    $res = convert::to_bool($res) ? 'true' : 'false';
                elseif (is_int($res))
                    $res = intval($res);
                else
                    $res = "'" . $res . "'";
                return $res;
            }

        }

        $configContent = file_get_contents(config_path() . '/site.config.php', 'r');
        preg_match_all("/[^\/]define\(['\"]([^'\"]*)['\"]/", $configContent, $matches);

        $out = "<?php\n";

        if (isset($matches[1]))
            foreach ($matches[1] as $m) {
                $out .= "define('{$m}'," . _ss($m) . ");\n";
            }

        if ($fp = @fopen(config_path() . '/site.config.php', 'w+'))
            if (fputs($fp, $out))
                return true;
        return false;
    }

    function themesetting() {
        $this->islogin();
        $model = new Setting;
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);


                $error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                foreach ($_POST as $type => $data) {
                    foreach ($data as $k => $v) {
                        $result = Setting::save_value($type, $k, $v);
                        if ($result === false)
                            $error = 1;
                    }
                } 
                
                if (!$error) {
                    $json_out['save_code'] = 1;
                    $json_out['save_txt'] = L::alert_data_save;
                } else {
                    $json_out['save_code'] = 0;
                    $json_out['save_txt'] = L::alert_err_in_saving_data;
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

}