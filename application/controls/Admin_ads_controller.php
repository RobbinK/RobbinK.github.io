<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_ads_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */


class Admin_adsController extends AdministrationController {

    protected $_model = array('Ad', 'Zone');

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_ads');
    }

    private function deleteCacheSettings() {
        rrmdir(cache_path() . '/etc/ads');
    }

    public function zone() {
        $this->islogin();
        $pk = 'id';
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $this->Zone->select()->where(array($pk => intval(@$_POST['id'])))->exec()->current;
                $arrsize = array('300x250', '728x90', '160x600', '100x100', '468x60', '120x600', '336x280', '200x200');
                if (count($found) > 0) {
                    if (!empty($found['adsize']) && !in_array($found['adsize'], $arrsize)) {
                        list ($w, $h) = explode('x', $found['adsize']);
                        $found['adsize'] = 'custom';
                        $found['width'] = trim($w);
                        $found['height'] = trim($h);
                    } else {
                        $found = array_merge($found, array('adsize' => $found['adsize'], 'width' => 0, 'height' => 0));
                    }
                    $found['zone_snipped'] = htmlentities("<?=  ab_show_ad('" . strtolower($found['zone_name']) . "')?>");
                    echo json_encode($found);
                }
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $this->Zone->delete()->where(array($pk => $id))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $this->Zone->delete()->where($pk . " in ({$ides})")->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {

                $this->getPOST($_POST);
                $savearray = array(
                    'zone_name' => @$_POST['zone_name'],
                    'type' => @$_POST['type'],
                    'show_ad' => @$_POST['show_ad'],
                );
                if ($_POST['type'] == 'banner') {
                    if ($_POST['adsize'] == 'custom')
                        $savearray['adsize'] = $_POST['width'] . 'x' . $_POST['height'];
                    else
                        $savearray['adsize'] = @$_POST['adsize'];
                } else {
                    $savearray['adsize'] = null;
                }

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    if (empty($_POST[$pk])) {
                        if ($this->Zone->insert($savearray)->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                            $json_out['insid'] = $this->Zone->lastinsid();
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $this->Zone->update($savearray)->where(array($pk => $_POST[$pk]))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                            $json_out['zone_snipped'] = htmlentities("<?=  ab_show_ad('" . strtolower($savearray['zone_name']) . "')?>");
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                echo json_encode($json_out);
                exit;
            }
            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array($pk, 'zone_name', 'type', 'adsize', '(select count(*) from abs_ads where zone_id=abs_zones.id)', 'show_ad');
                $this->Zone->select(join(',', $aColumns));
                pengu_user_load_class('ab_jdatatable', $jdt, $this->Zone);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();
                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {

                        if ($aColumns[$f] == 'show_ad') {
                            if ($v == 1)
                                $v = L::forms_single_ads;
                            if ($v == 2)
                                $v = L::forms_multiple_ads_random;
                            if ($v == 3)
                                $v = L::forms_multiple_ads_ordered;
                        }
                        if ($aColumns[$f] == 'type') {
                            switch ($v) {
                                case 'banner':
                                    $v = L::forms_banner_area;
                                    break;
                                case 'popunder':
                                    $v = L::forms_popunder;
                                    break;
                                case 'skin':
                                    $v = L::forms_skin_ads;
                                    break;
                                case 'anchor':
                                    $v = L::forms_anchore_ads;
                                    break;
                            }
                        }
                    }
                    $url = url::router('admin-ads')->fulluri(array('zone_id' => $row[0]));
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>" .
                            "<a href='{$url}' title='Manage' class='icon-hdd'></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function countries() {
        $this->islogin();
        global $router;
        $pk = 'id';
        $countries = '';
        $zone = array();
        if (isset($_GET['id'])) {
            $zone['id'] = $this->Ad->select()->where(array($pk => intval($_GET['id'])))->exec()->current()->zone_id;
            $this->set('data', $this->Ad->select()->where(array('id' => intval($_GET['id'])))->exec()->current()->countries);
            $this->set('zone', $zone);
        }
        if (validate::_is_ajax_request()) {
            if (isset($_GET['save'])) {
                $zone_id = $this->Ad->select()->where(array($pk => $_POST['id']))->exec()->current()->zone_id;
                if (isset($_POST['cons']))
                    $countries = join(',', $_POST['cons']);
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    if (!empty($_POST[$pk])) {
                        if (false !== $this->Ad->update(array('countries' => $countries))->where(array($pk => $_POST[$pk]))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                            ($_POST['btnsave'] != 'Save') ? $json_out['url'] = url::router('admin-ads')->fulluri(array('zone_id' => $zone_id)) : $json_out['url'] = null;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                        $this->deleteCacheSettings();
                        $this->cleanMysqlCache('ads');
                    }
                }
                echo json_encode($json_out);
                exit;
            }
        }
    }

    public function ads() {
        $this->islogin();
        global $router;
        $countries = require app_path() . '/lib/agent/countries.php';
        $countries_str = join(',', array_keys($countries));
        $pk = 'id';
        if (!isset($_GET['zone_id']) || !$zoneid = intval($_GET['zone_id'])) {
            $this->notfound();
            return;
        }

        $this->view->zone = $this->Zone->select('*,(select count(*) from abs_ads where zone_id=abs_zones.id) as total_ads')->where(array('abs_zones.id' => $zoneid))->exec()->current;

        if (validate::_is_ajax_request()) {
            if (isset($_GET['getdata'])) {
                $data = $this->Ad->select()->where(array('zone_id' => $zoneid))->orderby('`order` asc')->exec()->allrows();
                echo json_encode($data);
                exit;
            }
            if (isset($_GET['del'])) {
                $id = @intval($_POST['id']);
                $delC = 0;
                $delC = $this->Ad->delete()->where(array('id' => $id))->exec();
                if ($delC) {
                    echo $delC;
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                exit;
            }
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'zone_id' => $zoneid,
                    'adnetwork_title' => $_POST['adnetwork_title'],
                    'code' => base64::decode($_POST['code']),
                    'status' => $_POST['status'],
                    'order' => $_POST['order'],
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    if (empty($_POST[$pk])) {
                        $savearray = array_merge($savearray, array('countries' => $countries_str));
                        if ($this->Ad->insert($savearray)->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                            $json_out['lsid'] = $this->Ad->lastinsid();
                            $json_out['count_allcons'] = count($countries);
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $this->Ad->update($savearray)->where(array($pk => $_POST[$pk]))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                echo json_encode($json_out);
                exit;
            }
            if (isset($_GET['delall'])) {
                $ids = implode(',', $_POST['ids']);
                $delC = $this->Ad->delete()->where(array('id in(' . $ids . ')'))->exec();
                if ($delC) {
                    echo $delC;
                    $this->deleteCacheSettings();
                    $this->cleanMysqlCache('ads');
                }
                exit;
            }
            if (isset($_GET['saveall'])) {
                $this->deleteCacheSettings();
                $this->cleanMysqlCache('ads');
                exit;
            }
        }
    }

}