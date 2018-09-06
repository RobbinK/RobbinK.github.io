<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_links_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_linksController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_links');
    }

    public function links() {
        $this->islogin();
        $model = new Link();

        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {

                $found = $model->select()->where(array('id' => intval(@$_POST['id']), 'link_type' => 1))->exec();
                if ($found->numrows() > 0)
                    echo json_encode($found->current);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('id' => $id, 'link_type' => 1))->exec();
                if ($delC){
                    $this->cleanMysqlCache('links');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where("id in ({$ides})")->exec();
                if ($delC) {
                    $this->cleanMysqlCache('links');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'link_type' => 1,
                    'partner_title' => @$_POST['partner_title'],
                    'partner_url' => @$_POST['partner_url'],
                    'partner_email' => @$_POST['partner_email'],
                    'expire_date' => @$_POST['expire_date'],
                    'position' => @$_POST['position'],
                    'priority' => convert::to_integer(@$_POST['priority'], 0),
                    'status' => convert::to_bool(@$_POST['status']));
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                if (empty($_POST['partner_title'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_partner_title . '</li>';
                    $save_error = 1;
                }
                if (empty($_POST['partner_url']) || $_POST['partner_url'] == 'http://' || !(validate::_is_URL($_POST['partner_url']))) {
                    $json_out['save_txt'] .= '<li>' . L::alert_invalid_url_format . '</li>';
                    $save_error = 1;
                }

                if (!empty($_POST['expire_date']) && !(validate::_is_date($_POST['expire_date']))) {
                    $json_out['save_txt'] .='<li>' . L::alert_invalid_date_format . '</li>';
                    $save_error = 1;
                }
                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';

                if ($save_error == 0) {
                    if (empty($_POST['id'])) {
                        if ($model->insert($savearray)->exec()) {
                            $this->cleanMysqlCache('links');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array('id' => $_POST['id'], 'link_type' => 1))->exec()) {
                            $this->cleanMysqlCache('links');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }
                echo json_encode($json_out);
                exit;
            }
            //-- Data Table
            if (isset($_GET['dt'])) {

                $aColumns = array('id', 'partner_title', 'partner_url', 'partner_email', "expire_date", 'position', 'priority', "status");
                $model->select(join(',', $aColumns))->where("link_type=1");
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();


                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if (validate::_is_URL($v))
                            $v = "<a href='" . $v . "' target='_blank' class='external_link'>" . str::summarize($v, 60, false, '/') . "</a>";
                        if ($aColumns[$f] == 'status')
                            $v = convert::to_bool($v) ? "<span class='text-success'>" . L::global_enable . "</span>" : "<span class='text-error'>" . L::global_disable . "</span>";
                        if ($aColumns[$f] == 'partner_title')
                            $v = str::summarize($v, 60);
                        if ($aColumns[$f] == 'position') {
                            switch ($v) {
                                case 0: $v = 'HomePage Only';
                                    break;
                                case 1: $v = 'Internal Page';
                                    break;
                                case 2: $v = 'All Pages';
                                    break;
                                case 3: $v = 'Links Page';
                                    break;
                                default: $v = L::global_unknown;
                            }
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function link_exchange() {
        $this->islogin();
        $model = new Link();

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            //-- Refresh link
            if (isset($_POST['check_status'])) {
                $this->check_link_status_ajax();
                exit;
            }

            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $model->select()->where(array('id' => intval(@$_POST['id']), 'link_type' => 2))->exec();
                if ($found->numrows() > 0)
                    echo json_encode($found->current);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('id' => $id, 'link_type' => 2))->exec();
                if ($delC) {
                    $this->cleanMysqlCache('links');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where("id in ({$ides})")->exec();
                if ($delC) {
                    $this->cleanMysqlCache('links');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'link_type' => 2,
                    'local_url' => @$_POST['local_url'],
                    'show_page_url' => @$_POST['show_page_url'],
                    'partner_title' => @$_POST['partner_title'],
                    'partner_url' => @$_POST['partner_url'],
                    'partner_email' => @$_POST['partner_email'],
                    'expire_date' => @$_POST['expire_date'],
                    'position' => @$_POST['position'],
                    'priority' => convert::to_integer(@$_POST['priority'], 0),
                    'status' => convert::to_integer(@$_POST['status'], 0)
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                if (empty($_POST['partner_title'])) {
                    $json_out['save_txt'] .= ' -' . L::alert_invalid_partner_title . '<br> ';
                    $save_error = 1;
                }

                if (empty($_POST['local_url']) || $_POST['local_url'] == 'http://' || !(validate::_is_URL($_POST['local_url']))) {
                    $json_out['save_txt'] .= ' -' . L::alert_invalid_local_url . '<br> ';
                    $save_error = 1;
                }

                if (empty($_POST['show_page_url']) || $_POST['show_page_url'] == 'http://' || !(validate::_is_URL($_POST['show_page_url']))) {
                    $json_out['save_txt'] .= ' -' . L::alert_invalid_show_page_url . '<br> ';
                    $save_error = 1;
                }

                if (empty($_POST['partner_url']) || $_POST['partner_url'] == 'http://' || !(validate::_is_URL($_POST['partner_url']))) {
                    $json_out['save_txt'] .= ' -' . L::alert_invalid_partner_url . '<br> ';
                    $save_error = 1;
                }

                if (!empty($_POST['expire_date']) && !(validate::_is_date($_POST['expire_date']))) {
                    $json_out['save_txt'] .= ' -' . L::alert_invalid_expiration_date . '<br> ';
                    $save_error = 1;
                }

                if ($save_error == 0) {
                    if (empty($_POST['id'])) {
                        if ($model->insert($savearray)->exec()) {
                            $this->cleanMysqlCache('links');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array('id' => $_POST['id'], 'link_type' => 2))->exec()) {
                            $this->cleanMysqlCache('links');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }
                echo json_encode($json_out);
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {

                $aColumns = array('id', 'partner_title', 'partner_url', 'show_page_url', 'expire_date', 'position', 'priority', 'status', 'last_check', "IF(link_exists=1,'exists',IF(link_exists=-1,'not found','Unchecked'))");
                $model->select(implode(',', $aColumns))->where('link_type=2');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();


                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if (validate::_is_URL($v))
                            $v = "<a href='" . $v . "' target='_blank' class='external_link'>" . str::summarize($v, 50, false, '/') . "</a>";
                        if ($f == 9) {
                            $refresh_btn = "<input type='hidden' class='row_id_ref' value='{$row[0]}'/><i title='" . L::forms_check_availability . "'  class='icon-refresh check pull-left'></i>";
                            $lastcheck = '<em title="' . L::forms_last_check . '" style="font-size:9px;">' . ($row[8] > 0 ? date('Y-m-d', $row[8]) : '-') . '</em>';
                            switch ($v) {
                                case 'exists': $v = "<div style='width:80px;text-align:right;' class='chk label label-success'>{$refresh_btn}<span class='pull-right'>" . L::forms_available . "<br>{$lastcheck}</span></div>";
                                    break;
                                case 'not found': $v = "<div style='width:80px;text-align:right;' class='chk label label-important'>{$refresh_btn}<span class='pull-right'>" . L::forms_not_found . "<br>{$lastcheck}</span></div>";
                                    break;
                                case 'Unchecked': $v = "<div style='width:80px;text-align:right;' class='chk label'>{$refresh_btn}<span class='pull-right'>" . L::forms_unchecked . "<br>{$lastcheck}</span></div>";
                                    break;
                            }
                        }
                        if ($aColumns[$f] == 'status')
                            $v = convert::to_bool($v) ? "<span class='text-success'>" . L::global_enable . "</span>" : "<span class='text-error'>" . L::global_disable . "</span>";
                        if (($aColumns[$f] == 'partner_title') && (trim($v) != ''))
                            $v = str::summarize($v, 60);

                        if ($aColumns[$f] == 'position') {
                            switch ($v) {
                                case 0: $v = L::forms_homepage_only;
                                    break;
                                case 1: $v = L::forms_internal_page;
                                    break;
                                case 2: $v = L::forms_all_pages;
                                    break;
                                case 3: $v = L::forms_links_page;
                                    break;
                                default: $v = L::global_unknown;
                            }
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function check_link_status_ajax() {
        $this->islogin(true);
        $model = new Link();

        $link_id = intval($_POST['btn_id']);
        $array_cond = array('id' => $link_id, 'link_type' => 2);

        $records = $model->select()->where($array_cond)->exec();

        if ($records->numrows() > 0) {
            //echo $records->current()->local_url . ' - ' . $records->current()->show_page_url;
            $array_upd = array('link_exists' => -1, 'last_check' => time());
            $link_exists = -1;
            $link_exists_txt = L::forms_not_found;

            require_once(lib_path() . '/pengu_seo/pengu_seo.class.php');
            if (@pengu_seo::check_backlink($records->current()->show_page_url, $records->current()->local_url)) {
                $array_upd['link_exists'] = 1;
                $link_exists = 1;
                $link_exists_txt = L::forms_available;
            }
            $model->update($array_upd)->where($array_cond)->exec();

            $lscheck = '<em title="Last Check" style="font-size:9px;">' . date('Y-m-d', $array_upd['last_check']) . '</em>';
            $link_exists_txt .= "<br>{$lscheck}";
            echo json_encode(array('st' => $link_exists, 'txt' => $link_exists_txt));
        }
    }

    function request_comments() {
        $this->islogin();
        $model = new Comment();
        $pk = 'id';
        if (validate::_is_ajax_request()) {
            if (isset($_GET['editcm'])) {
                $this->request_comments_reply();
                return;
            }

            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'response' => @$_POST['response'],
                );

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    $model->update($savearray)->where(array('id' => $_POST['id']))->exec();
                    if ($this->send_mail($_POST['email'], "RE: Exchange request with " . Lib::get_domain(HOST_URL), $_POST['response'])) {
                        $json_out['save_code'] = 1;
                        $json_out['save_txt'] = L::alert_message_sent_to . "  {$_POST['email']}";
                    } else {
                        $json_out['save_code'] = 0;
                        $json_out['save_txt'] = L::alert_message_not_sent;
                    }
                }

                echo json_encode($json_out);
                exit;
            }

            $this->view->disable();
            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where($pk . " in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- Data Table
            if (isset($_GET['dt'])) {
                pengu_user_load_class('ab_bbcode', $bbcodeI);
                $aColumns = array(
                    'id', 'name', 'email', "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d %H:%i:%s')", 'comment', 'status', 'response');
                $model->select(implode(',', $aColumns))->where(array('type' => 2));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $iconshow = '';
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'comment') {
                            $v = str::summarize($v, 150);
                            $v = $bbcodeI->bbcode_decode($v);
                        }


                        if ($aColumns[$f] == 'status') {
                            if ($v == 0)
                                $v = "<span class='text-error'>" . L::forms_unread . "</span>";
                            elseif ($v == 1)
                                $v = "<span class='text-info'>" . L::forms_viewed . "</span>";
                        }

                        if ($aColumns[$f] == 'response' && !empty($v))
                            $v = "<i class='splashy-check'></i>";
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>" .
                            "<a href='#' title='" . L::dashboard_view_details . "' class='sepV_a'><i class='icon-eye-open observe'></i></a>" . $iconshow;
                }


                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function request_comments_reply() {
        $this->islogin();
        $this->MapViewFileName('request_comments_reply.php');
        $model = new Comment();
        $pk = 'id';

        $model->update(array('status' => 1))->where(array('id' => $_GET['id'], 'status' => 0))->exec();
        $aColumns = array(
            'id',
            'name',
            'email',
            'website',
            "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as date",
            'comment',
            'ip',
            'time',
            'response',
            'country'
        );
        $data = $model->select(implode(',', $aColumns))
                        ->where(array($pk => $_GET['id']))
                        ->exec()->current;

        pengu_user_load_class('ab_bbcode', $instance);
        $data['comment'] = $instance->bbcode_decode($data['comment']);
        $data['comment'] = nl2br($data['comment']);
        $this->set($data);
    }

}