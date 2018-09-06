<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_categories_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:56
##########################################################
 */


class Admin_categoriesController extends AdministrationController
{

    protected $_model = null;

    function __construct()
    {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_categories');
    }

    function categories()
    {
        $this->islogin();
        $model = new category();

        $pk = 'cid';
        //--Upload Icon 
        if (isset($_GET['uploadfile']) || isset($_FILES['uploadfile'])) {
            $this->view->disable();

            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('uploadfile');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = 'cat_' . lib::rand(15);
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
        //-- </Upload Image> --//

        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {

                $found = $model->select()->where(array($pk => intval(@$_POST['id'])))->exec();
                if ($found->numrows() > 0) {
                    $data = $found->current;
                    $data['feed_tag_matching'] = @explode(',', $data['feed_tag_matching']);
                    echo json_encode($data);
                }
                exit;
            }

            //-- deleting Icon
            if (isset($_GET['del_file'])) {
                if (isset($_POST['db_field']) && intval(@$_POST['id']) > 0) {
                    $records = $model->select($_POST['db_field'])->where(array($pk => $_POST['id']))->exec();
                    if (!empty($records->current[$_POST['db_field']])) {
                        $file = trim($records->current[$_POST['db_field']]);
                        if (file_exists(ab_tmp_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_tmp_dir . '/' . $file);
                        elseif (file_exists(ab_upload_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_upload_dir . '/' . $file);
                        $del = $model->update(array($_POST['db_field'] => ''))->where(array($pk => $_POST['id']))->exec();
                        if ($del)
                            exit(json_encode(array('delete_code' => 1, 'delete_txt' => L::alert_file_removed)));
                    }
                    exit(json_encode(array('delete_code' => 0, 'delete_txt' => L::alert_file_not_found)));
                }
            }
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

            //-- Saving
            if (isset($_GET['save'])) {

                $this->getPOST($_POST);

                $savearray = array(
                    'title' => @$_POST['title'],
                    'seo_title' => convert::seoText(@$_POST['title']),
                    'feed_tag_matching' => @(is_array($_POST['feed_tag_matching']) ? join(',', $_POST['feed_tag_matching']) : $_POST['feed_tag_matching']),
                    'meta_description' => @$_POST['meta_description'],
                    'meta_keywords' => @$_POST['meta_keywords'],
                    'description' => @$_POST['description'],
                    'featured' => @$_POST['featured'],
                    'is_active' => @$_POST['is_active'],
                    'icon' => @$_POST['icon']);
                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if (empty($_POST['title'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_category_title . '</li>';
                    $save_error = 1;
                }
                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';

                if ($save_error == 0) {
                    if (file_exists(ab_tmp_dir . '/' . $savearray['icon'])) {
                        @copy(ab_tmp_dir . '/' . $savearray['icon'], ab_upload_dir . '/' . $savearray['icon']);
                        @unlink(ab_tmp_dir . '/' . $savearray['icon']);
                    }

                    if (empty($_POST[$pk])) {
                        if ($model->insert($savearray)->exec()) {
                            $this->cleanMysqlCache('categories');
                            if ($this->generate_sitemap() !== false) {
                                $json_out['save_code'] = 1;
                                $json_out['save_txt'] = L::alert_record_save;
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_txt'] = L::alert_no_permission . ": " . setting::get_data('sitemap_file_name', 'val');
                            }
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($pk => $_POST[$pk]))->exec()) {
                            $this->cleanMysqlCache('categories');
                            if ($this->generate_sitemap() !== false) {
                                $json_out['save_code'] = 1;
                                $json_out['save_txt'] = L::alert_record_update;
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_txt'] = L::alert_no_permission . ": " . setting::get_data('sitemap_file_name', 'val');
                            }
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
                $aColumns = array($pk, 'title', 'meta_keywords', 'meta_description', 'if(featured=1,"yes","no")', 'if(is_active=1,"enabled","disabled")');
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
                        if ($f == 1)
                            $v="<a href='".url::router('admingames')->fulluri(array('cat'=>$row[0]))."'>".str::summarize($v, 60)."</a>";
                        if ($f == 5)
                            $v = convert::to_bool($v) ? "<span class='text-success'>" . L::global_enable . "</span>" : "<span class='text-error'>" . L::global_disable . "</span>";
                        if ($f == 4)
                            $v = convert::to_bool($v) ? "<span class='text-success'>" . L::global_state_yes . "</span>" : "<span class='text-error'>" . L::global_state_no . "</span>";
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

}