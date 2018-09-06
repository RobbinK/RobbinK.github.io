<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_members_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_membersController extends AdministrationController {

    protected $_model = null;

    function __construct() {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_members');
    }

    public function members() {
        $this->islogin();
        $model = new Member;
        //--Upload Avatar 
        if (isset($_GET['uploadfile']) || isset($_FILES['uploadfile'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('uploadfile');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = 'avatar_' . lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }
        if (isset($_GET['getkey'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_getkey();
            exit;
        }
        if (isset($_GET['progresskey'])) {
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_st($_GET['progresskey']);
            exit;
        }
        if (isset($_GET['showimage'])) {
            $this->preview_img(ab_image_path($_GET['showimage']));
            exit;
        }
        //--End Upload Avatar


        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- Edit record
            if (isset($_GET['edit'])) {

                $found = $model->select("
                    id,
                    username,
                    `group`,
                    email,
                    name,
                    avatar,
                    quote,
                    gender,
                    website,
                    msn,
                    skipe,
                    icq,
                    aim,
                    yahoo,
                    google_talk,
                    status,
                    if(lastlogin>0, DATE_FORMAT(FROM_UNIXTIME(lastlogin),'%Y-%m-%d %H:%i') ,null) as lastlogin,
                    login,
                    regdate,
                    ip_info_range,
                    ip_info_country
                    ")->where(array('id' => intval(@$_POST['id'])))->exec();
                if ($found->numrows() > 0) {
                    $rows = $found->current;
                    if ($country = agent::country($rows['ip_info_country']))
                        $rows['ip_info_country'] = $country['country'];
                    echo json_encode($rows);
                }
                exit;
            }
            //-- deleting avatar
            if (isset($_GET['del_avatar']) && isset($_POST['id']) && intval(@$_POST['id']) > 0 && _dbaffecting()) {
                $records = $model->select()->where(array('id' => $_POST['id']))->exec();
                $icon_file = trim($records->current['avatar']);
                @unlink(ab_upload_dir . '/' . $icon_file);
                $model->update(array('avatar' => ''))->where(array('id' => $_POST['id']))->exec();
                if ($icon_file == '')
                    echo L::alert_avatar_not_found;
                else
                    echo L::alert_avatar_removed;
                exit;
            }
            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array('id' => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where("id in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'username' => $_POST['username'],
                    'group' => $_POST['group'],
                    'email' => $_POST['email'],
                    'name' => $_POST['name'],
                    'avatar' => $_POST['avatar'],
                    'quote' => $_POST['quote'],
                    'gender' => @$_POST['gender'],
                    'website' => $_POST['website'],
                    'msn' => $_POST['msn'],
                    'skipe' => $_POST['skipe'],
                    'icq' => $_POST['icq'],
                    'aim' => $_POST['aim'],
                    'yahoo' => $_POST['yahoo'],
                    'google_talk' => $_POST['google_talk'],
                    'status' => @$_POST['status']
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';
                /*
                 * 
                 * Some Validation
                 * 
                 */
                if ($save_error == 0) {
                    if (file_exists(ab_tmp_dir . '/' . $savearray['avatar'])) {
                        @copy(ab_tmp_dir . '/' . $savearray['avatar'], ab_upload_dir . '/' . $savearray['avatar']);
                        @unlink(ab_tmp_dir . '/' . $savearray['avatar']);
                    }

                    if (!empty($_POST['password']))
                        $savearray = array_merge($savearray, array('password' => md5($_POST['password'])));
                    if (empty($_POST['id'])) {
                        $savearray = array_merge($savearray, array('regdate' => date('Y-m-d')));
                        if ($model->insert($savearray)->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array('id' => $_POST['id']))->exec()) {
                            /* admin approval */
                            $aresult = $model->select()->where(array('id' => $_POST['id'], 'status' => 1, "LENGTH(`confirm`)>=15 and `confirm`<>'confirmed'"))->exec();
                            if ($aresult->found()) {
                                $this->send_mail_by_tpl($aresult->current()->email, 'Confirm Your Registation', array(
                                    'name' => $aresult->current()->name,
                                    'username' => $aresult->current()->username,
                                    'email' => $aresult->current()->email,
                                    'domain' => HOST_NAME,
                                    'login_link' => HOST_URL . url::router('userlogin')->fulluri(),
                                        ), 'membership_approval_admin');
                                $model->update(array('confirm' => 'confirmed'))->where(array('id' => $_POST['id']))->exec();
                            }
                            /* --------------- */
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
                $aColumns = array(
                    'M.id',
                    'username',
                    'G.group_name',
                    'name',
                    'regdate',
                    'if(lastlogin>0, DATE_FORMAT(FROM_UNIXTIME(lastlogin),"%Y-%m-%d  %H:%i") ,null)',
                    'login',
                    'if(status=1,"Active",if(status=-1,"Banned",""))',
                );
                $model->alias('M')->select(implode(',', $aColumns))->innerjoin('abs_members_group', 'G')->on('M.group=G.id');
                if (Admin::data('group') != 1)
                    $model->where('`group` not in (1,3)');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if ($f == 7) {
                            switch ($v) {
                                case 'Active' : $v = "<span class='text-success'>" . L::global_enable . "</span>";
                                    break;
                                case 'Banned' : $v = "<span class='text-error'>" . L::forms_banned . "</span>";
                                    break;
                                default: $v = "<span class='text-info'>" . L::global_pending . "</span>";
                                    break;
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
        if (Admin::data('group') == 1){
            $model = new MemberGroup;
            $memberGroups = $model->select()->where('id not in (5,6)')->exec(); // except facebook and google members
        }
        else {
            $model = new MemberGroup;
            $memberGroups = $model->select()->where('id not in (1,3,5,6)')->exec(); // except facebook and google members and administration group
        }
        $this->view->MemberGroup = $memberGroups;
    }

    function bannedmembers() {
        $this->islogin();
        $model = new Member;

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            //-- multi active
            if (isset($_GET['mactive'])) {
                if (!empty($_POST['id'])) {
                    $ides = join(',', $_POST['id']);
                    $json_out['save_txt'] = '';
                    $activeC = $model->update(array('status' => 1))->where("id in ({$ides})")->exec();

                    echo "{$activeC} " . L::alert_members_activate;
                } else
                    echo L::alert_no_members;
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $aColumns = array(
                    'M.id',
                    'username',
                    'G.group_name',
                    'name',
                    'regdate',
                    'if(lastlogin>0, DATE_FORMAT(FROM_UNIXTIME(lastlogin),"%Y-%m-%d  %H:%i") ,null)',
                    'login',
                    'if(status=0,"Active",if(status=-1,"Banned",""))',
                );
                $model->alias('M')->select(implode(',', $aColumns))->innerjoin('abs_members_group', 'G')->on('M.group=G.id')->where(array('status' => -1));
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                foreach ($myrows as &$row) {
                    foreach ($row as $f => &$v) {
                        if (in_array($v, array('Active', 'Banned'))) {
                            switch ($v) {
                                case 'Active' : $v = "<span class='text-success'>" . L::global_enable . "</span><input type='hidden' class='row_id' value='{$row[0]}' />";
                                    break;
                                case 'Banned' : $v = "<span class='text-error'>" . L::forms_banned . "</span><input type='hidden' class='row_id' value='{$row[0]}' />";
                                    break;
                            }
                        }
                    }
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
        $this->view->MemberGroup = MemberGroup::getAll();
    }

    function massemail() {
        $this->islogin();
        $members = new Member;
    }

    function adminprofile() {
        $this->islogin();
        $model = new Admin();
        //--Upload Avatar 
        if (isset($_GET['uploadfile']) || isset($_FILES['uploadfile'])) {
            $this->view->disable();

            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('uploadfile');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = 'avatar_' . lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }
        if (isset($_GET['getkey'])) {
            $this->view->disable();
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_getkey();
            exit;
        }
        if (isset($_GET['progresskey'])) {
            pengu_user_load_class('ab_interface_upload', $instance);
            echo $instance->proccess_st($_GET['progresskey']);
            exit;
        }
        if (isset($_GET['showimage'])) {
            $this->preview_img(ab_image_path($_GET['showimage']));
            exit;
        }
        //--End Upload 
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- deleting avatar
            if (isset($_GET['del_avatar']) && isset($_POST['id']) && intval(@$_POST['id']) > 0 && _dbaffecting()) {
                $records = $model->select()->where(array('id' => $_POST['id']))->exec();
                $icon_file = trim($records->current['avatar']);
                @unlink(ab_upload_dir . '/' . $icon_file);
                $model->update(array('avatar' => ''))->where(array('id' => $_POST['id']))->exec();
                if ($icon_file == '')
                    echo L::alert_file_not_found;
                else
                    echo L::alert_avatar_removed;
                exit;
            }
            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'email' => $_POST['email'],
                    'name' => $_POST['name'],
                    'avatar' => $_POST['avatar'],
                );
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                /*
                 * 
                 * Some Validation
                 * 
                 */
                if ($save_error == 0) {
                    if (file_exists(ab_tmp_dir . '/' . $savearray['avatar'])) {
                        @copy(ab_tmp_dir . '/' . $savearray['avatar'], ab_upload_dir . '/' . $savearray['avatar']);
                        @unlink(ab_tmp_dir . '/' . $savearray['avatar']);
                    }

                    function savedata($savearray, $json_out) {
                        $model = new Admin();
                        if (false !== $model->update($savearray)->where(array('id' => Admin::data('id')))->exec()) {
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                        return $json_out;
                    }

                    if (!empty($_POST['password'])) {
                        if (md5($_POST['old_password']) == Admin::data('password')) {
                            $result = $model->ChangePass($_POST['password'], $_POST['old_password'], Admin::data('username'));
                            if ($result > 0) {
                                $json_out = savedata($savearray, $json_out);
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_txt'] = L::alert_password_not_changed;
                            }
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_invalid_old_password;
                        }
                    } else {
                        $json_out = savedata($savearray, $json_out);
                    }
                }
                echo json_encode($json_out);
                exit;
            }
        }
        //get
        $this->view->admin = $model->select()->where(array('id' => Admin::data('id')))->exec()->current();
    }

}
