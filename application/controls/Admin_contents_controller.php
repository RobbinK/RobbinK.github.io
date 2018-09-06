<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_contents_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_contentsController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_contents');
    }

    function blocks() {
        $this->islogin();
        $pk = 'id';
        $model = new Block();
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $model->select("
                    {$pk},
                    block_title,
                    block_content, 
                    status
                    ")->where(array($pk => intval(@$_POST['id'])))->exec();
                $out = array();
                if ($found->numrows() > 0) {
                    $data = $found->current;
                    $data['block_content'] = input::unsafe($found->current['block_content']);
                    $data['block_snipped'] = htmlentities("<?=  ab_block_content('block-" . $found->current['id'] . "')?>"); 
                    echo json_encode($data);
                }
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC) {
                    $this->cleanMysqlCache('contents');
                    echo "{$delC} ".L::alert_records_delete;
                }
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where("{$pk} in ({$ides})")->exec();
                if ($delC) {
                    $this->cleanMysqlCache('contents');
                    echo "{$delC} ".L::alert_records_delete;
                }
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                if (empty($_POST['block_title'])) {
                    $json_out['save_txt'] .= '-'.L::alert_fill_block_title.'<br>';
                    $save_error = 1;
                }

                if ($save_error == 0) {
                    $savearray = array(
                        'block_title' => $_POST['block_title'],
                        'block_content' => $_POST['block_content'],
                        'status' => $_POST['status']
                    );
                    if (empty($_POST['id'])) {
                        if ($model->insert($savearray)->exec()) {
                            $this->cleanMysqlCache('contents');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                            $json_out['insid'] = $model->lastinsid();
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($pk => $_POST['id']))->exec()) {
                            $this->cleanMysqlCache('contents');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                            $json_out['block_snipped'] = htmlentities("<?=  ab_block_content('block-" . $_POST['id'] . "')?>");
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

                $aColumns = array($pk, 'block_title', 'status');
                $model->select(implode(',', $aColumns));
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
                            $v = "<a href='" . $v . "' target='_blank' class='external_link'>" . str::summarize($v, 25, true, '/') . "</a>";
                        if ($aColumns[$f] == 'status')
                            $v = convert::to_bool($v) ? "<span class='text-success'>".L::global_enable."</span>" : "<span class='text-error'>".L::global_disable."</span>";
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='".L::global_edit."' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='".L::global_remove."' class='sepV_a'><i class='icon-trash del'></i></a>";
                    $row['extra'] = 'hrmll';
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function pages() {
        $this->islogin();
        $pk = 'pid';
        $model = new Page();
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $model->select("
                   {$pk} as id,
                    page_title,
                    meta_keywords,
                    meta_description,
                    page_content,
                    page_visit,
                    page_access,
                    status
                    ")->where(array($pk => intval(@$_POST['id'])))->exec();
                $out = array();
                if ($found->numrows() > 0) {
                    $data = $found->current;
                    $data['page_content'] = input::unsafe($found->current['page_content']);
                    echo json_encode($data);
                }
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($pk => $id))->exec();
                if ($delC) {
                    $this->cleanMysqlCache('contents');
                    echo "{$delC} ".L::alert_records_delete;
                }
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where("{$pk} in ({$ides})")->exec();
                if ($delC) {
                    $this->cleanMysqlCache('contents');
                    echo "{$delC} ".L::alert_records_delete;
                }
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                if (empty($_POST['page_title'])) {
                    $json_out['save_txt'] .= '-'.L::alert_fill_page_title.'<br>';
                    $save_error = 1;
                }

                if ($save_error == 0) {
                    $savearray = array(
                        'page_title' => $_POST['page_title'],
                        'seo_title' => convert::seoText($_POST['page_title']),
                        'meta_description' => $_POST['meta_description'],
                        'meta_keywords' => $_POST['meta_keywords'],
                        'page_content' => $_POST['page_content'],
                        'page_access' => $_POST['page_access'],
                        'status' => $_POST['status']
                    );
                    if (empty($_POST['id'])) {
                        if ($model->insert($savearray)->exec()) {
                            $this->cleanMysqlCache('contents');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($pk => $_POST['id']))->exec()) {
                            $this->cleanMysqlCache('contents');
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

                $aColumns = array($pk, 'page_title', 'meta_keywords', 'page_visit', 'if(page_access=1,"Everyone","Just Members")', 'status');
                $model->select(implode(',', $aColumns));
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
                            $v = "<a href='" . $v . "' target='_blank' class='external_link'>" . str::summarize($v, 25, true, '/') . "</a>";
                        if ($aColumns[$f] == 'status')
                            $v = convert::to_bool($v) ? "<span class='text-success'>".L::global_enable."</span>" : "<span class='text-error'>".L::global_disable."</span>";
                        if ($aColumns[$f] == 'page_title')
                            $v = str::summarize($v, 60);
                        if ($aColumns[$f] == 'meta_keywords')
                            $v = str::summarize($v, 60);
                        if ($aColumns[$f] == 'meta_description')
                            $v = str::summarize($v, 60);
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='".L::global_edit."' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                            "<a href='#' title='".L::global_remove."' class='sepV_a'><i class='icon-trash del'></i></a>";
                    $row['extra'] = 'hrmll';
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

}