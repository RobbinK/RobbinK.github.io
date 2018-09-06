<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_posts_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_postsController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_posts');
    }

    public function link_sale() {
        $this->islogin();
        $model = new Post_Log();
        $this->MapViewFileName('link_sale.php');
        $post_type = 1;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/linksale/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='View' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function site_sale() {
        $this->islogin();
        $model = new Post_Log();
        $this->MapViewFileName('site_sale.php');
        $post_type = 2;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/sitesale/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='View' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function domain_sale() {
        $this->islogin();
        $model = new Post_Log();
        $this->MapViewFileName('domain_sale.php');
        $post_type = 3;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/domainsale/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='View' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function game_sponsorship() {
        $this->islogin();
        $model = new Post_Log();
        $this->MapViewFileName('game_sponsorship.php');
        $post_type = 4;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/gamesponsorship/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='View' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
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
        $model = new Post_Log();
        $this->MapViewFileName('link_exchange.php');
        $post_type = 5;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/linkexchange/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='View' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    public function arcade_discussions() {
        $this->islogin();
        $model = new Post_Log();
        $this->MapViewFileName('arcade_discussions.php');
        $post_type = 6;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            if (isset($_GET['showid'])) {
                $model = new Post_Log();
                $model->update(array('is_read' => 1))->where(array('post_type' => $post_type, 'post_id' => $_GET['showid']))->exec();
                echo @file_get_contents(master_url . '/post/discussions/' . $_GET['showid']);
                exit;
            }

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('post_id' => $id, 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($delC) {
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }
            //-- multi unread
            if (isset($_GET['unread'])) {
                $ides = join(',', $_POST['id']);
                $unread = $model->update(array('is_read' => 1))->where(array("post_id in ({$ides})", 'post_type' => $post_type))->exec();
                if ($unread) {
                    echo "{$unread} " . L::alert_marked_read;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array('post_id', "is_read", 'post_title', 'username', "DATE_FORMAT(FROM_UNIXTIME(insert_time), '%Y-%m-%d %H:%i')");
                $model->select(join(',', $aColumns))->where(array('post_type' => $post_type));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    $read = false;
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'is_read')
                            if (convert::to_bool($v)) {
                                $read = true;
                                $v = '<i class="splashy-mail_light_stuffed"></i>';
                            }
                            else
                                $v = '<i class="splashy-mail_light"></i>';
                        if ($aColumns[$f] == 'post_title' && !$read) {
                            $v = '<b>' . $v . '</b>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                            "<a href='#' title='" . L::forms_show . "' class='sepV_a'><i class='icon-eye-open open'></i></a>" .
                            "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function comments() {
        $this->islogin();
        $model = new Comment();
        $pk = 'id';
        if (validate::_is_ajax_request()) {
            if (isset($_GET['editcm'])) {
                $this->comment_reply();
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
                    if ($this->send_mail($_POST['email'], "RE: Your message in  " . Lib::get_domain(HOST_URL), $_POST['response'])) {
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
                    'id', 'name', 'email', 'type', "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d %H:%i:%s')", 'comment', 'status', 'response');
                $model->select(implode(',', $aColumns))->where(array('type in (3,4,5)'));
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
                        if ($aColumns[$f] == 'type') {
                            switch ($v) {
                                case 3:$v = 'Game Exchange Request';
                                    break;
                                case 4:$v = 'Support';
                                    break;
                                case 5:$v = 'Other Questions';
                                    break;
                            }
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
                            "<a href='#' title='Observe' class='sepV_a'><i class='icon-eye-open observe'></i></a>" . $iconshow;
                }


                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function comment_reply() {
        $this->islogin();
        $this->MapViewFileName('comments_reply.php');
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
