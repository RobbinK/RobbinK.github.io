<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_games_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_gamesController extends AdministrationController
{

    protected $_model = null;

    function __construct()
    {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_games');

        if (!file_exists(ab_game_files_dir))
            rmkdir(ab_game_files_dir);

        if (!file_exists(ab_game_images_dir))
            rmkdir(ab_game_images_dir);

        function up_game_checking_duplicate()
        {
            if (isset($_GET['gamename'])) {
                if (Game::check_duplicate(rawurldecode($_GET['gamename']), @$_GET['gid'])) {
                    /* game is duplicated */
                    echo json_encode(array('success' => false,
                        'msg' => L::alert_game_exists));
                    exit;
                }
            }
        }

        function grab_game_checking_duplicate()
        {
            if (isset($_POST['gamename'])) {
                if (Game::check_duplicate(rawurldecode($_POST['gamename']), @$_POST['id'])) {
                    /* game is duplicated */
                    echo json_encode(array('grab_code' => 0,
                        'grab_txt' => L::alert_game_exists));
                    exit;
                }
            }
        }

        function up_mobilegame_checking_duplicate()
        {
            if (isset($_GET['gamename'])) {
                if (MobileGame::check_duplicate(rawurldecode($_GET['gamename']), @$_GET['gid'])) {
                    /* game is duplicated */
                    echo json_encode(array('success' => false,
                        'msg' => L::alert_game_exists));
                    exit;
                }
            }
        }

        function grab_mobilegame_checking_duplicate()
        {
            if (isset($_POST['gamename'])) {
                if (MobileGame::check_duplicate(rawurldecode($_POST['gamename']), @$_POST['id'])) {
                    /* game is duplicated */
                    echo json_encode(array('grab_code' => 0,
                        'grab_txt' => L::alert_game_exists));
                    exit;
                }
            }
        }

    }

    private function deleteGames($model, $fkey, $ides)
    {
        if (is_numeric($ides)) {
            $id = $ides;
            $data = $model->select()->where(array($fkey => $id))->exec();
            if ($data->numrows()) {
                if (!empty($data->current()->game_img) && _dbaffecting())
                    @unlink(ab_game_images_dir . '/' . $data->current()->game_img);
                if (!empty($data->current()->featured_img) && _dbaffecting())
                    @unlink(ab_game_images_dir . '/' . $data->current()->featured_img);
                if (!empty($data->current()->game_file) && _dbaffecting())
                    @unlink(ab_game_files_dir . '/' . $data->current()->game_file);
                return $model->delete()->where(array($fkey => $id))->exec();
            }
        } elseif (is_string($ides)) {
            $data = $model->select()->where($fkey . " in ({$ides})")->exec();
            if ($data->numrows()) {
                while ($data->fetch()) {
                    if (!empty($data->current()->game_img) && _dbaffecting())
                        @unlink(ab_game_images_dir . '/' . $data->current()->game_img);
                    if (!empty($data->current()->featured_img) && _dbaffecting())
                        @unlink(ab_game_images_dir . '/' . $data->current()->featured_img);
                    if (!empty($data->current()->game_file) && _dbaffecting())
                        @unlink(ab_game_files_dir . '/' . $data->current()->game_file);
                }
                return $model->delete()->where($fkey . " in ({$ides})")->exec();
            }
        }
    }

    function games()
    {
        $this->islogin();
        $model = new game();
        $fkey = 'gid';
        $file_name_mode = Setting::get_data('file_names_mode', 'val');
        //-- <Upload Image> --//  
        if (isset($_GET['up_game_img']) || isset($_FILES['up_game_img'])) {
            $this->view->disable();
            up_game_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_game_img');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        if (isset($_GET['up_featured_img']) || isset($_FILES['up_featured_img'])) {
            $this->view->disable();
            up_game_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_featured_img');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        if (isset($_GET['up_game_slide_image']) || isset($_FILES['up_game_slide_image'])) {
            $this->view->disable();
            up_game_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_game_slide_image');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        if (isset($_GET['up_game_file']) || isset($_FILES['up_game_file'])) {
            $this->view->disable();
            up_game_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_game_file');
            $upload->setValidExtentions(array('swf', 'dcr', 'unity3d'));
            $new_file_name = lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name)) {
                @list($width, $height) = getimagesize(ab_tmp_dir . '/' . $file);
                echo json_encode(array('success' => true, 'file' => $file, 'width' => $width, 'height' => $height));
            } else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        if (isset($_GET['getkey'])) {
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
            $this->preview_img(ab_game_thumb_path($_GET['showimage']));
            exit;
        }

        //-- </Upload Image> --//  
        if (validate::_is_ajax_request()) {
            //--gettags
            if (isset($_GET['gettags'])) {
                $model = new Game_tag();
                $data = $model->select()->where(array("name like '{$_GET['gettags']}%'"))->exec();
                $out = array();
                if ($data->found()) {
                    while ($row = $data->fetch()) {
                        $out[] = $row->name;
                    }
                }
                echo json_encode($out);
                exit;
            }
            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $model->alias('G')
                    ->select()
                    ->where(array($fkey => intval(@$_POST['id'])))
                    ->exec();

                if ($found->numrows() > 0) {
                    $data = $found->current;

                    if (($data['game_file_source'] == 2)) {
                        $data['iframe_game_file'] = $data['game_file'];
                    }
                    if (($data['game_file_source'] == 3)) {
                        $data['link_game_file'] = $data['game_file'];
                    }
                    if (($data['game_file_source'] == 4)) {
                        $data['embedded_game_file'] = $data['game_file'];
                    }
                    $data['game_categories'] = @explode(',', $data['game_categories']);

                    if ($data['ribbon_expiration'] > time()) {
                        $data['ribbon_expiration'] = round((intval($data['ribbon_expiration']) - time()) / (3600 * 24))/* days */
                        ;
                    } else
                        $data['ribbon_expiration'] = 0 /* days */
                        ;

                    if (preg_match('/[^\d\,]/', $data['game_tags'])) {
                        $data['game_tags'] = explode(',', $data['game_tags']);
                    } else {
                        $data['game_tags'] = explode(',', Game_tag::getTagsName($data['game_tags']));
                    }
                    echo json_encode($data);
                }
                exit;
            }
            //****** Grab Codes
            if (isset($_GET['act_grab_game_file'])) {
                set_time_limit(5 * 60);
                $this->getPOST($_POST);

                grab_game_checking_duplicate();

                $fileaddr = str_replace(' ', '%20', $_POST['from']);
                if ($x = @file_get_contents($fileaddr)) {
                    $ext = path::get_extension($fileaddr);
                    $name = lib::rand(15);
                    $basename = convert::filesafe($name . '.' . $ext, true);
                    if (!preg_match("/swf|dcr|unity3d/i", $ext)) {
                        exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_invalid_file_format)));
                    } else {
                        $newGameFile = ab_tmp_dir . '/' . $basename;
                        if ($handle = fopen($newGameFile, "w")) {
                            if (fwrite($handle, $x)) {
                                @list($width, $height) = getimagesize($newGameFile);
                                $out = (json_encode(array('grab_code' => 1, 'grab_txt' => L::alert_game_file_upload, 'file' => $basename, 'width' => $width, 'height' => $height)));
                            }
                            fclose($handle);
                            permup($newGameFile);
                            exit($out);
                        }
                    }
                }
                exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_no_response)));
                exit;
            }
            if (isset($_GET['act_grab_game_img'])) {
                set_time_limit(5 * 60);
                $this->getPOST($_POST);

                grab_game_checking_duplicate();

                $fileaddr = str_replace(' ', '%20', $_POST['from']);
                if ($x = @file_get_contents($fileaddr)) {
                    $ext = path::get_extension($fileaddr);
                    $name = lib::rand(15);
                    $basename = convert::filesafe($name . '.' . $ext, true);
                    if (!preg_match("/jpg|jpeg|gif|png|bmp/i", $ext)) {
                        exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_invalid_image_format)));
                    } else {
                        $newimg = ab_tmp_dir . '/' . $basename;
                        if ($handle = fopen($newimg, "w")) {
                            if (fwrite($handle, $x)) {
                                $out = (json_encode(array('grab_code' => 1, 'grab_txt' => L::alert_game_image_upload, 'file' => $basename)));
                            }
                            fclose($handle);
                            permup($newimg);
                            exit($out);
                        }
                    }
                }
                exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_no_response)));
                exit;
            }
            if (isset($_GET['act_grab_featured_img'])) {
                set_time_limit(5 * 60);
                $this->getPOST($_POST);

                grab_game_checking_duplicate();

                $fileaddr = str_replace(' ', '%20', $_POST['from']);
                if ($x = @file_get_contents($fileaddr)) {
                    $ext = path::get_extension($fileaddr);
                    $name = lib::rand(15);
                    $basename = convert::filesafe($name . '.' . $ext, true);
                    if (!preg_match("/jpg|jpeg|gif|png|bmp/i", $ext)) {
                        exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_invalid_image_format)));
                    } else {
                        $newimg = ab_tmp_dir . '/' . $basename;
                        if ($handle = fopen($newimg, "w")) {
                            if (fwrite($handle, $x)) {
                                $out = (json_encode(array('grab_code' => 1, 'grab_txt' => L::alert_game_image_upload, 'file' => $basename)));
                            }
                            fclose($handle);
                            /* change perm */
                            permup($newimg);
                            exit($out);
                        }
                    }
                }
                exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_no_response)));
                exit;
            }

            //-- deleting Icon
            if (isset($_GET['del_file'])) {
                if (isset($_POST['db_field']) && intval(@$_POST['id']) > 0) {
                    $records = $model->select($_POST['db_field'])->where(array($fkey => $_POST['id']))->exec();
                    if (!empty($records->current[$_POST['db_field']])) {
                        $file = trim($records->current[$_POST['db_field']]);
                        if (file_exists(ab_tmp_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_tmp_dir . '/' . $file);
                        elseif (file_exists(ab_game_images_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_game_images_dir . '/' . $file);
                        $del = $model->update(array($_POST['db_field'] => ''))->where(array($fkey => $_POST['id']))->exec();
                        if ($del)
                            exit(json_encode(array('delete_code' => 1, 'delete_txt' => L::alert_file_removed,)));
                    }
                }
                if (!empty($_POST['filename']) && file_exists(ab_tmp_dir . '/' . $_POST['filename']))
                    exit(json_encode(array('delete_code' => 1, 'delete_txt' => L::alert_file_removed,)));
                exit(json_encode(array('delete_code' => 0, 'delete_txt' => L::alert_file_not_found)));
            }


            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $this->deleteGames($model, $fkey, $id);
                if ($delC) {
                    $this->cleanMysqlCache('games');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $this->deleteGames($model, $fkey, $ides);
                if ($delC) {
                    $this->cleanMysqlCache('games');
                    echo "{$delC} " . L::alert_records_delete;
                }
                exit;
            }

            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);
                $savearray = array(
                    'game_name' => @$_POST['game_name'],
                    'seo_title' => convert::seoText(@$_POST['game_name']),
                    'game_categories' => @(is_array($_POST['game_categories']) ? join(',', $_POST['game_categories']) : $_POST['game_categories']),
                    'game_description' => @$_POST['game_description'],
                    'game_meta_description' => @$_POST['game_meta_description'],
                    'game_instruction' => @$_POST['game_instruction'],
                    'game_controls' => @$_POST['game_controls'],
                    'game_tags' => join(',', Game_tag::tags_to_ids(@$_POST['game_tags'])),
                    'game_keywords' => @$_POST['game_keywords'],
                    'ribbon_type' => $_POST['ribbon_type'],
                    'ribbon_expiration' => (intval($_POST['ribbon_expiration']) > 0) ? time() + (intval($_POST['ribbon_expiration']) * 24 * 3600) : 0,
                    'game_image_source' => @$_POST['game_image_source'],
                    'game_img' => @$_POST['game_img'],
                    'featured_img' => @$_POST['featured_img'],
                    'game_show_slide' => @convert::to_bool($_POST['game_show_slide']),
                    'game_slide_image' => @$_POST['game_slide_image'],
                    'game_file_source' => @$_POST['game_file_source'],
                    'game_file' => @$_POST['game_file'],
                    'game_url_parameters' => @ltrim($_POST['game_url_parameters'], '?'),
                    'game_width' => @$_POST['game_width'],
                    'game_height' => @$_POST['game_height'],
                    'game_is_featured' => @$_POST['game_is_featured'],
                    'game_upddate' => time(),
                    'game_is_active' => @$_POST['game_is_active']);

                if (@$_POST['game_file_source'] == 2) {
                    $savearray['game_file'] = @$_POST['iframe_game_file'];
                }

                if (@$_POST['game_file_source'] == 3) {
                    $savearray['game_file'] = @$_POST['link_game_file'];
                }

                if (@$_POST['game_file_source'] == 4) {
                    $savearray['game_file'] = @$_POST['embedded_game_file'];
                }

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if (empty($_POST['game_name'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_game_name . '</li>';
                    $save_error = 1;
                }
                if (empty($_POST['game_categories'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_game_categories . '</li>';
                    $save_error = 1;
                }

                if (Game::check_duplicate($_POST['game_name'], @$_POST[$fkey])) {
                    /* game is duplicated */
                    $json_out['save_txt'] .= '<li>' . L::alert_game_exists . '</li>';
                    $save_error = 1;
                }

                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';

                if ($save_error == 0) {
                    //  MOve from temp
                    if (!empty($savearray['game_img']) && file_exists(ab_tmp_dir . '/' . $savearray['game_img'])) {

                        if ($file_name_mode == 'random')
                            $filename = lib::rand(15);
                        else
                            $filename = convert::filesafe($savearray['game_name']);

                        $ext = path::get_extension($savearray['game_img']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_img'], ab_game_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_img']);
                        $savearray['game_img'] = $newfilename;
                    }

                    if (!empty($savearray['featured_img']) && file_exists(ab_tmp_dir . '/' . $savearray['featured_img'])) {

                        if ($file_name_mode == 'random')
                            $filename = lib::rand(15);
                        else
                            $filename = convert::filesafe($savearray['game_name']) . '_featured';

                        $ext = path::get_extension($savearray['featured_img']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['featured_img'], ab_game_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['featured_img']);
                        $savearray['featured_img'] = $newfilename;
                    }

                    if (!empty($savearray['game_slide_image']) && file_exists(ab_tmp_dir . '/' . $savearray['game_slide_image'])) {


                        if ($file_name_mode == 'random')
                            $filename = lib::rand(15);
                        else
                            $filename = convert::filesafe($savearray['game_name']) . '_slide';


                        $ext = path::get_extension($savearray['game_slide_image']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_slide_image'], ab_game_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_slide_image']);
                        $savearray['game_slide_image'] = $newfilename;
                    }

                    if (!empty($savearray['game_file']) && file_exists(ab_tmp_dir . '/' . $savearray['game_file'])) {

                        if ($file_name_mode == 'random')
                            $filename = lib::rand(15);
                        else
                            $filename = convert::filesafe($savearray['game_name']);

                        $ext = path::get_extension($savearray['game_file']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_files_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_file'], ab_game_files_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_file']);
                        $savearray['game_file'] = $newfilename;
                    }
                    //
                    if (empty($_POST[$fkey])) {
                        if ($model->insert(array_merge($savearray, array('game_adddate' => time())))->exec()) {
                            $this->cleanMysqlCache('games');
                            if ($this->generate_sitemap() !== false) {
                                $json_out['save_code'] = 1;
                                $json_out['save_txt'] = L::alert_record_save;
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_txt'] = L::alert_no_permission . ' ' . setting::get_data('sitemap_file_name', 'val');
                            }
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($fkey => $_POST[$fkey]))->exec()) {
                            $this->cleanMysqlCache('games');
                            if ($this->generate_sitemap() !== false) {
                                $json_out['save_code'] = 1;
                                $json_out['save_txt'] = L::alert_record_update;
                            } else {
                                $json_out['save_code'] = 0;
                                $json_out['save_txt'] = L::alert_no_permission . ' ' . setting::get_data('sitemap_file_name', 'val');
                            }
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }
                echo json_encode(array_merge($json_out, $savearray));
                exit;
            }

            //-- Data Table 
            if (isset($_GET['dt'])) {
                $aColumns = array(
                    $fkey, //0
                    'game_rating', //1
                    'game_is_featured', //2
                    'featured_img', //3
                    'game_img', //4
                    'game_name', //5
                    'game_categories', //6
                    'game_today_hits', //7
                    'game_total_hits', //8
                    'game_last_hit', //9
                    "if(game_file_source=0 or game_file_source=1,SUBSTRING_INDEX(game_file,'.',-1),  if(game_file_source=2 or game_file_source=3,'remote',if(game_file_source=4,'code','broken') )  )", //10
                    'if(game_is_active=1,"enabled",if(game_is_active=0,"queue","disabled"))', //11
                );
                $model->select(implode(',', $aColumns));
                if (isset($_GET['cat'])) {
                    if (!is_numeric($_GET['cat']))
                        $catID = Category::getCategoryIDBySeo($_GET['cat']);
                    else
                        $catID = $_GET['cat'];
                    $model->where("concat(',',game_categories,',') like '%," . $catID . ",%'");
                }
                //$model->innerjoin('abs_categories')->on('abs_games.game_categories=abs_categories.cid');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                $out = array();
                foreach ($myrows as $row) {

                    switch ($row[10]) {
                        case 'dcr' :
                            $type = "<span class='icon-dcr' title='DCR'></span>";
                            break;
                        case 'swf' :
                            $type = "<span class='icon-swf' title='SWF'></span>";
                            break;
                        case 'unity3d' :
                            $type = "<span class='icon-unity' title='Unity3D'></span>";
                            break;
                        case 'remote' :
                            $type = "<span class='icon-link' title='" . L::forms_remote . "'></span>";
                            break;
                        case 'code' :
                            $type = "<span class='icon-code' title='" . L::forms_embedded . "'></span>";
                            break;
                        default :
                            $type = "<span class='icon-broken' title='" . L::forms_broken . "'></span>";
                            break;
                    }

                    switch ($row[11]) {
                        case 'enabled' :
                            $active = "<span class='text-success'>" . L::global_enable . "</span>";
                            break;
                        case 'queue' :
                            $active = "<span class='text-info'>" . L::forms_in_queue . "</span>";
                            break;
                        case 'disabled' :
                            $active = "<span class='text-error'>" . L::global_disable . "</span>";
                            break;
                    }

                    $title = str::summarize($row[5], 60);
                    $title .= '<p>' .
                        '<div class="sepH_b progress progress-warning" style="width: 100px;height: 7px;" title="' . L::forms_popularity . ': ' . @round($row[1]) . '%"><div style="width: ' . @round($row[1]) . '%" class="bar"></div></div>' .
                                (convert::to_bool($row[2]) ? '<i class="splashy-star_full"></i>' : null) .
                                '</p>';

                    // generate categories
                    $categories = null;
                    $sep = null;
                    $cats = explode(',', $row[6]);
                    foreach ($cats as $catID) {
                        $categories .= $sep."<a href='".url::itself()->url_nonqry()."?cat=$catID'>" . Category::getCategoriesTitle($catID) . "</a>";
                        $sep = ' ,';
                    }

                    $actions = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";


                    //== game image
                    $img = '';
                    if (!empty($row[3]) && file_exists(ab_game_images_dir . '/' . $row[3]))
                        $img = $row[3];
                    else if (!empty($row[4]) && file_exists(ab_game_images_dir . '/' . $row[4]))
                        $img = $row[4];

                    $image = '<div class="thumbnail">'
                        . "<img src=\"" . ab_game_create_img($img, 80, null) . "\" data-fullimage=\"" . ab_game_get_image_url($img) . "\"  rel=\"clbox\" >" .
                        '</div>';

                    global $agoLanguage;
                    $out[] = array(
                        null, //fkey:0
                        null, //game_rating:1
                        null, //game_is_featured:2
                        null, //featured_img:3
                        $image, //game_img:4
                        $title, //game_name:5
                        "<div>{$categories}</div>", //game_categories:6
                        number_format($row[7]), //game_today_hits:7
                        number_format($row[8]), //game_total_hits:8
                        intval($row[9]) ? pengu_date::ago($row[9], lang_isrtl(), $agoLanguage) : '-', //game_last_hit:9
                        $type, //game_is_active:10
                        $active, //game_is_active:11
                        $actions,
                    );
                }

                $jdt->setData($out);
                echo $jdt->renderOutput();
                exit;
            }
        }
        //--get all categories
        $model_cat = new category;
        $this->view->categoriesaout = $model_cat->select('cid,title')->orderby('title')->exec()->allrows();
    }

    function mobilegamse()
    {
        $this->islogin();
        $model = new MobileGame();
        $fkey = 'gid';
        //-- <Upload Image> --// 

        if (isset($_GET['up_game_img']) || isset($_FILES['up_game_img'])) {
            $this->view->disable();
            up_mobilegame_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_game_img');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = lib::rand(15);
            if ($file = $upload->upload(ab_tmp_dir, $new_file_name))
                echo json_encode(array('success' => true, 'file' => $file));
            else
                echo json_encode(array('success' => false, 'msg' => $upload->getErrorMsg()));
            return;
            exit;
        }

        if (isset($_GET['up_featured_img']) || isset($_FILES['up_featured_img'])) {
            $this->view->disable();
            up_mobilegame_checking_duplicate();
            pengu_user_load_class('ab_interface_upload', $upload);
            $upload->addToIndex('up_featured_img');
            $upload->setValidExtentions(array('jpg', 'png', 'gif', 'jpeg'));
            $new_file_name = lib::rand(15);
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
            $this->preview_img(ab_game_thumb_path($_GET['showimage']));
            exit;
        }

        //-- </Upload Image> --//  
        if (validate::_is_ajax_request()) {
            $this->view->disable();
            //-- getdata
            if (isset($_GET['edit'])) {
                $found = $model->alias('G')
                    ->select()
                    ->where(array($fkey => intval(@$_POST['id'])))
                    ->exec();

                if ($found->numrows() > 0) {
                    $data = $found->current;
                    $data['game_categories'] = @explode(',', $data['game_categories']);

                    echo json_encode($data);
                }
                exit;
            }
            //****** Grab Codes 
            if (isset($_GET['act_grab_game_img'])) {
                set_time_limit(5 * 60);
                $this->getPOST($_POST);

                grab_mobilegame_checking_duplicate();

                $fileaddr = str_replace(' ', '%20', $_POST['from']);
                if ($x = @file_get_contents($fileaddr)) {
                    $ext = path::get_extension($fileaddr);
                    $name = lib::rand(15);
                    $basename = convert::filesafe($name . '.' . $ext, true);
                    if (!preg_match("/jpg|jpeg|gif|png|bmp/i", $ext)) {
                        exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_invalid_image_format)));
                    } else {
                        $newimg = ab_tmp_dir . '/' . $basename;
                        if ($handle = fopen($newimg, "w")) {
                            if (fwrite($handle, $x)) {
                                $out = (json_encode(array('grab_code' => 1, 'grab_txt' => L::alert_game_image_upload, 'file' => $basename)));
                            }
                            fclose($handle);
                            permup($newimg);
                            exit($out);
                        }
                    }
                }
                exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_no_response)));
                exit;
            }

            if (isset($_GET['act_grab_featured_img'])) {
                set_time_limit(5 * 60);
                $this->getPOST($_POST);

                grab_mobilegame_checking_duplicate();

                $fileaddr = str_replace(' ', '%20', $_POST['from']);
                if ($x = @file_get_contents($fileaddr)) {
                    $ext = path::get_extension($fileaddr);
                    $name = lib::rand(15);
                    $basename = convert::filesafe($name . '.' . $ext, true);
                    if (!preg_match("/jpg|jpeg|gif|png|bmp/i", $ext)) {
                        exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_invalid_image_format)));
                    } else {
                        $newimg = ab_tmp_dir . '/' . $basename;
                        if ($handle = fopen($newimg, "w")) {
                            if (fwrite($handle, $x)) {
                                $out = (json_encode(array('grab_code' => 1, 'grab_txt' => L::alert_game_image_upload, 'file' => $basename)));
                            }
                            fclose($handle);
                            permup($newimg);
                            exit($out);
                        }
                    }
                }
                exit(json_encode(array('grab_code' => 0, 'grab_txt' => L::alert_no_response)));
                exit;
            }

            //-- deleting Icon
            if (isset($_GET['del_file'])) {
                if (isset($_POST['db_field']) && intval(@$_POST['id']) > 0) {
                    $records = $model->select($_POST['db_field'])->where(array($fkey => $_POST['id']))->exec();
                    if (!empty($records->current[$_POST['db_field']])) {
                        $file = trim($records->current[$_POST['db_field']]);
                        if (file_exists(ab_tmp_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_tmp_dir . '/' . $file);
                        elseif (file_exists(ab_game_images_dir . '/' . $file) && _dbaffecting())
                            @unlink(ab_game_images_dir . '/' . $file);
                        $del = $model->update(array($_POST['db_field'] => ''))->where(array($fkey => $_POST['id']))->exec();
                        if ($del)
                            exit(json_encode(array('delete_code' => 1, 'delete_txt' => 'The file is deleted',)));
                    }
                }
                if (!empty($_POST['filename']) && file_exists(ab_tmp_dir . '/' . $_POST['filename']))
                    exit(json_encode(array('delete_code' => 1, 'delete_txt' => L::alert_file_removed,)));
                exit(json_encode(array('delete_code' => 0, 'delete_txt' => L::alert_file_not_found)));
            }


            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $this->deleteGames($model, $fkey, $id);
                if ($delC) {
                    $this->cleanMysqlCache('games');
                    echo "{$delC} " . L::alert_records_delete;;
                }
                exit;
            }


            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $this->deleteGames($model, $fkey, $ides);
                if ($delC) {
                    $this->cleanMysqlCache('games');
                    echo "{$delC} " . L::alert_records_delete;;
                }
                exit;
            }

            //-- Saving
            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $savearray = array(
                    'game_name' => @$_POST['game_name'],
                    'seo_title' => convert::seoText(@$_POST['game_name']),
                    'game_categories' => @(is_array($_POST['game_categories']) ? join(',', $_POST['game_categories']) : $_POST['game_categories']),
                    'game_description' => @$_POST['game_description'],
                    //'game_instruction' => @$_POST['game_instruction'],
                    //'game_controls' => @$_POST['game_controls'],
                    'game_meta_description' => @$_POST['game_meta_description'],
                    'game_keywords' => @$_POST['game_keywords'],
                    'game_tags' => @$_POST['game_tags'],
                    'game_image_source' => @$_POST['game_image_source'],
                    'game_img' => @$_POST['game_img'],
                    'featured_img' => @$_POST['featured_img'],
                    'game_android_link' => @$_POST['game_android_link'],
                    'game_ios_link' => @$_POST['game_ios_link'],
                    'game_html5_link' => @$_POST['game_html5_link'],
                    'game_is_featured' => @$_POST['game_is_featured'],
                    'game_upddate' => time(),
                    'game_is_active' => @$_POST['game_is_active']);
                if (@$_POST['game_file_source'] == 3) {
                    $savearray['game_file'] = @$_POST['link_game_file'];
                }


                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if (empty($_POST['game_name'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_game_name . '</li>';
                    $save_error = 1;
                }
                if (empty($_POST['game_categories'])) {
                    $json_out['save_txt'] .= '<li>' . L::alert_fill_game_categories . '</li>';
                    $save_error = 1;
                }

                if (MobileGame::check_duplicate($_POST['game_name'], @$_POST[$fkey])) {
                    /* game is duplicated */
                    $json_out['save_txt'] .= '<li>' . L::alert_game_exists . '</li>';
                    $save_error = 1;
                }

                if ($save_error == 1)
                    $json_out['save_txt'] = '<ul class="list_d">' . $json_out['save_txt'] . '</ul>';

                if ($save_error == 0) {
                    //  MOve from temp
                    if (!empty($savearray['game_img']) && file_exists(ab_tmp_dir . '/' . $savearray['game_img'])) {
                        $filename = convert::filesafe($savearray['game_name']);
                        $ext = path::get_extension($savearray['game_img']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['game_img'], ab_game_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['game_img']);
                        $savearray['game_img'] = $newfilename;
                    }

                    if (!empty($savearray['featured_img']) && file_exists(ab_tmp_dir . '/' . $savearray['featured_img'])) {
                        $filename = convert::filesafe($savearray['game_name']) . '_featured';
                        $ext = path::get_extension($savearray['featured_img']);
                        $newfilename = $filename . '.' . $ext;
                        $i = 1;
                        while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                            $newfilename = $filename . '_' . $i . '.' . $ext;
                            $i++;
                        }
                        @copy(ab_tmp_dir . '/' . $savearray['featured_img'], ab_game_images_dir . '/' . $newfilename);
                        @unlink(ab_tmp_dir . '/' . $savearray['featured_img']);
                        $savearray['featured_img'] = $newfilename;
                    }

                    //
                    if (empty($_POST[$fkey])) {
                        if ($model->insert(array_merge($savearray, array('game_adddate' => time())))->exec()) {
                            $this->cleanMysqlCache('games');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_save;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    } else {
                        if (false !== $model->update($savearray)->where(array($fkey => $_POST[$fkey]))->exec()) {
                            $this->cleanMysqlCache('games');
                            $json_out['save_code'] = 1;
                            $json_out['save_txt'] = L::alert_record_update;
                        } else {
                            $json_out['save_code'] = 0;
                            $json_out['save_txt'] = L::alert_err_in_saving_data;
                        }
                    }
                }
                $patharray = array(
                    'game_img' => $savearray['game_img'],
                    'featured_img' => $savearray['featured_img'],
                );
                echo json_encode(array_merge($json_out, $patharray));
                exit;
            }

            //-- Data Table 
            if (isset($_GET['dt'])) {
                $aColumns = array(
                    $fkey, //0
                    'game_rating', //1
                    'game_is_featured', //2
                    'featured_img', //3
                    'game_img', //4
                    'game_name', //5
                    'game_categories', //6
                    'game_today_hits', //7
                    'game_total_hits', //8
                    'game_last_hit', //9
                    'if(length(game_html5_link)>10,"html5",
                        if(length(game_android_link)>10 and length(game_ios_link)>10,"andios",
                            if(length(game_android_link)>10,"android",
                                 if(length(game_ios_link)>10,"ios","")
                            )
                          )
                       )', //10
                    'if(game_is_active=1,"enabled",if(game_is_active=0,"queue","disabled"))', //11
                );
                $model->select(implode(',', $aColumns));
                //$model->innerjoin('abs_categories')->on('abs_games.game_categories=abs_categories.cid');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();

                $out = array();
                foreach ($myrows as $row) {

                    switch ($row[11]) {
                        case 'enabled' :
                            $active = "<span class='text-success'>" . L::global_enable . "</span>";
                            break;
                        case 'queue' :
                            $active = "<span class='text-info'>" . L::forms_in_queue . "</span>";
                            break;
                        case 'disabled' :
                            $active = "<span class='text-error'>" . L::global_disable . "</span>";
                            break;
                    }

                    $mobileType = '';
                    if ($row[10] == 'android')
                        $mobileType = '<img src="img/android.png" style="height:20px"/>';
                    elseif ($row[10] == 'andios')
                        $mobileType = '<img src="img/andios.png" style="height:30px"/>';
                    elseif ($row[10] == 'ios')
                        $mobileType = '<img src="img/apple.png" style="height:25px"/>';
                    elseif ($row[10] == 'html5')
                        $mobileType = '<img src="img/html5.png" style="height:25px"/>';

                    $title = str::summarize($row[5], 60);
                    $title .= '<p>' .
                        '<div class="sepH_b progress progress-warning" style="width: 100px;height: 7px;" title="' . L::forms_popularity . ': ' . @round($row[1]) . '%"><div style="width: ' . @round($row[1]) . '%" class="bar"></div></div>' .
                                (convert::to_bool($row[2]) ? '<i class="splashy-star_full"></i>' : null) .
                                '</p>';

                    $categories = MobileCategory::getCategoriesTitle($row[6]);

                    $actions = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<a href='#' title='" . L::global_edit . "' class='sepV_a'><i class='icon-pencil edit'></i></a>" .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";


                    //== game image
                    $img = '';
                    if (!empty($row[3]) && file_exists(ab_game_images_dir . '/' . $row[3]))
                        $img = $row[3];
                    else if (!empty($row[4]) && file_exists(ab_game_images_dir . '/' . $row[4]))
                        $img = $row[4];

                    $image = '<div class="thumbnail">'
                        . "<img src=\"" . ab_game_create_img($img, 80, null) . "\" data-fullimage=\"" . ab_game_get_image_url($img) . "\"  rel=\"clbox\" >" .
                        '</div>';
                    global $agoLanguage;
                    $out[] = array(
                        null, //fkey:0
                        null, //game_rating:1
                        null, //game_is_featured:2
                        null, //featured_img:3
                        $image, //game_img:4
                        $title, //game_name:5
                        str::summarize($categories, 45), //game_categories:6
                        number_format($row[7]), //game_today_hits:7
                        number_format($row[8]), //game_total_hits:8
                        intval($row[9]) ? pengu_date::ago($row[9], lang_isrtl(), $agoLanguage) : '-', //game_last_hit:9
                        $mobileType, //mobile type:10
                        $active, //game_is_active:11
                        $actions,
                    );
                }

                $jdt->setData($out);
                echo $jdt->renderOutput();
                exit;
            }
        }
        //--get all categories
        $model_cat = new category;
        $this->view->categoriesaout = $model_cat->select('cid,title')->orderby('title')->exec()->allrows();
    }

    function games2()
    {
        $this->islogin();
        $model = new game();
        $fkey = 'gid';

        if (validate::_is_ajax_request()) {
            $this->getPOST($_POST);
        }
        //--get all categories
        $model_cat = new category;
        $this->view->categoriesaout = $model_cat->select('cid,title')->exec()->allrows();
    }

    function editgame()
    {
        $this->games();
    }

    function games_broken()
    {
        $this->islogin();
        $model = new Game_broken();
        $fkey = 'id';

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($fkey => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }


            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where($fkey . " in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            if (isset($_GET['dt'])) {
                $aColumns = array($fkey, 'game_id', 'abs_games.game_img', 'game_name', 'comment', 'user', "DATE_FORMAT(FROM_UNIXTIME(date),'%Y-%m-%d   %H:%i')", 'status');
                $model->select(implode(',', $aColumns));
                $model->innerjoin('abs_games')->on('abs_games.gid=abs_games_broken.game_id');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();
                $ids = array();
                foreach ($myrows as &$row) {
                    $ids[] = $row[0];
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'game_name') {
                            $v = '<a href="#">' . $v . '</a>';
                        }
                        if ($aColumns[$f] == 'comment') {
                            $sm = str::summarize($v, 70);
                            if (strlen($sm) < strlen(trim($v))) {
                                $sm .= '<div style="display:none" class="tx"><div style="min-height:200px;width:400px;background:#fff;padding:10px;line-height:18px;">' . $v . '</div></div> <a href="#" class="text-info tx-more">more</a>';
                            }
                            $v = $sm;
                        }
                        if ($aColumns[$f] == 'status')
                            $v = ($v == 1) ? "<span class='text-success'>" . L::global_read . "</span>" : "<span class='text-error'>" . L::global_not_read . "</span>";

                        /* game image */
                        if ($aColumns[$f] == 'abs_games.game_img') {
                            $img = '';
                            if (!empty($row[2]) && file_exists(ab_game_images_dir . '/' . $row[2]))
                                $img = $row[2];
                            $imageht = "<img  src=\"" . ab_game_create_img($img, 50, null) . "\" data-fullimage=\"" . ab_game_get_image_url($img) . "\"  rel=\"clbox\" >";
                            $row[2] = '<div class="thumbnail">' .
                                "<a  class='hint--right' data-hint='{$row[3]}' >" .
                                $imageht .
                                "</a>" .
                                '</div>';
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<input type='hidden' class='game_id' value='{$row[1]}' />" .
                        //"<a href='#' title='".L::global_remove."' class='sepV_a'><i class='icon-trash del'></i></a>" .
                        "<a href='#' class='btn btn-mini fix'><i class='icon-edit'></i> " . L::global_edit . "</button>";
                }
                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                //update to read
                $model->update(array('status' => 1))->where("id in (" . join(',', $ids) . ")")->exec();
                //
                exit;
            }
        }
    }

    function games_submited()
    {
        $this->islogin();
        $model = new Game_submited();
        $fkey = 'id';

        if (isset($_GET['showimage'])) {
            $this->preview_img(ab_game_thumb_path($_GET['showimage']));
            exit;
        }

        if (isset($_GET['openg'])) {
            $this->MapViewFileName('games_submited_open.php');
            $id = intval($_GET['id']);
            $model->update(array('status' => 1))->where(array('id' => $id, 'status' => 0))->exec();
            $this->view->game = $model->alias('s')->select('s.*,abs_members.name')->leftjoin('abs_members')->on('abs_members.id=s.user_id')->where(array('s.id' => $id))->exec()->current();
            return;
        }

        if (validate::_is_ajax_request()) {
            $this->view->disable();

            //-- Approving
            if (isset($_GET['approve'])) {
                $id = intval(@$_POST['id']);
                $email = @$_POST['email'];
                $data = $model->select()->where(array($fkey => $id))->exec();
                if (!$data->numrows())
                    return false;
                $current = $data->current();
                //check already approved
                if ($current->status == 2) {
                    exit(json_encode(array('msg' => L::alert_already_approved, 'type' => 'st-error')));
                }
                //check duplicated
                if (Game::check_duplicate(@$current->game_name)) {
                    exit(json_encode(array('type' => 'st-error', 'msg' => L::alert_game_exists)));
                }
                //  Move from temp
                if (!empty($current->game_img) && file_exists(ab_submission_images_dir . '/' . $current->game_img)) {
                    $filename = convert::filesafe($current->game_name);
                    $ext = path::get_extension($current->game_img);
                    $newfilename = $filename . '.' . $ext;
                    $i = 1;
                    while (file_exists(ab_game_images_dir . '/' . $newfilename)) {
                        $newfilename = $filename . '_' . $i . '.' . $ext;
                        $i++;
                    }
                    @copy(ab_submission_images_dir . '/' . $current->game_img, ab_game_images_dir . '/' . $newfilename);
                    //@unlink(ab_submission_images_dir . '/' . $current->game_img);
                    $current->game_img = $newfilename;
                }
                if (!empty($current->game_file) && file_exists(ab_submission_files_dir . '/' . $current->game_file)) {
                    $filename = convert::filesafe($current->game_name);
                    $ext = path::get_extension($current->game_file);
                    $newfilename = $filename . '.' . $ext;
                    $i = 1;
                    while (file_exists(ab_game_files_dir . '/' . $newfilename)) {
                        $newfilename = $filename . '_' . $i . '.' . $ext;
                        $i++;
                    }
                    @copy(ab_submission_files_dir . '/' . $current->game_file, ab_game_files_dir . '/' . $newfilename);
                    //@unlink(ab_submission_files_dir . '/' . $current->game_file);
                    $current->game_file = $newfilename;
                }
                //
                $save = array(
                    'game_name' => $current->game_name,
                    'seo_title' => convert::seoText($current->game_name),
                    'game_description' => $current->game_description,
                    'game_instruction' => $current->game_instruction,
                    'game_controls' => $current->game_controls,
                    'game_tags' => @join(',', Game_tag::tags_to_ids($current->game_tags)),
                    'game_img' => $current->game_img,
                    'game_file' => $current->game_file,
                    'game_width' => $current->game_width,
                    'game_height' => $current->game_height,
                    'game_image_source' => 0,
                    'game_file_source' => 0,
                    'game_adddate' => time(),
                    'game_upddate' => time(),
                    'game_is_active' => 0
                );
                $gameModel = new Game();
                $moved = $gameModel->insert($save)->exec();
                if ($moved) {
                    $upd = $model->update(array('status' => 2))->where(array($fkey => $id))->exec();
                    if ($upd) {
                        /* send email to submittor */
                        $gameLink = url::router('pregame2', array('game_seo' => $save['seo_title']))->fulluri();
                        $subject = "Your game was approved.";
                        $msg = "Your submitted game ({$current->game_name}) was approved and added to our games list.<br> Play Page Link: <a href='{$gameLink}'>{$gameLink}</a>";
                        if (!empty($email))
                            $this->send_mail($email, $subject, $msg);

                        exit(json_encode(array('msg' => "Game  is approved.", 'type' => 'st-success', 'insid' => $gameModel->lastinsid())));
                    }
                }
                exit(json_encode(array('msg' => "Error!", 'type' => 'st-error')));
            }
            //-- deleting
            if (isset($_GET['del'])) {
                $id = intval(@$_POST['id']);
                $delC = $model->delete()->where(array($fkey => $id))->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }


            //-- multi delete
            if (isset($_GET['mdel'])) {
                $ides = join(',', $_POST['id']);
                $delC = $model->delete()->where($fkey . " in ({$ides})")->exec();
                if ($delC)
                    echo "{$delC} " . L::alert_records_delete;
                exit;
            }
            //user_id,game_name,game_description,game_tags,game_file,game_categories,status
            if (isset($_GET['dt'])) {
                $aColumns = array('s.' . $fkey, 'game_name', 'game_categories', 'game_description', 'name', "DATE_FORMAT(FROM_UNIXTIME(addtime),'%Y-%m-%d   %H:%i')", 's.status');
                $model->alias('s')->select(implode(',', $aColumns));
                $model->leftjoin('abs_members')->on('abs_members.id=s.user_id');
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();
                $ids = array();
                foreach ($myrows as &$row) {
                    $ids[] = $row[0];
                    foreach ($row as $f => &$v) {
                        if ($aColumns[$f] == 'game_description') {
                            $v = str::summarize($v, 80);
                        }
                        if ($aColumns[$f] == 's.status') {
                            switch ($v) {
                                case 0:
                                    $v = "<span class='text-error'>" . L::global_not_read . "</span>";
                                    break;
                                case 1:
                                    $v = "<span class='text-info'>" . L::global_read . "</span>";
                                    break;
                                case 2:
                                    $v = "<span class='text-success'>" . L::global_moved . "</span>";
                                    break;
                            }
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<a href='#' title='" . L::forms_show . "' class='sepV_a'><i class='icon-eye-open openg'></i></a>" .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }
                $jdt->setData($myrows);
                echo $jdt->renderOutput();
            }
        }
    }

    function gamecomments_reply()
    {
        $this->islogin();
        $this->MapViewFileName('gamecomments_reply.php');
        $model = new Comment();
        $pk = 'C.id';

        $model->update(array('reviewed' => 1))->where(array('id' => $_GET['id']))->exec();
        $aColumns = array(
            'C.id',
            'G.game_name as game_name',
            'M.username as user_name',
            'C.email as email',
            'C.website as website',
            "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d') as date",
            'comment',
            'ip',
            'time',
            'response',
            'country'
        );
        $data = $model->alias('C')->select(implode(',', $aColumns))->leftjoin('abs_games', 'G')->on('C.group=G.gid')
            ->leftjoin('abs_members', 'M')->on('M.id=C.user_id')
            ->where(array($pk => $_GET['id']))
            ->exec()->current;

        pengu_user_load_class('ab_bbcode', $instance);
        $data['comment'] = $instance->bbcode_decode($data['comment']);
        $this->set($data);
    }

    function gamecomments()
    {
        $this->islogin();
        $model = new Comment();
        $pk = 'id';
        if (validate::_is_ajax_request()) {
            if (isset($_GET['editcm'])) {
                $this->gamecomments_reply();
                return;
            }

            if (isset($_GET['save'])) {
                $this->getPOST($_POST);

                $savearray = array(
                    'response' => @$_POST['response'],
                    'status' => 2
                );

                $save_error = 0;
                $json_out['save_code'] = 0;
                $json_out['save_txt'] = '';

                if ($save_error == 0) {
                    $model->update($savearray)->where(array('id' => $_POST['id']))->exec();
                    if ($this->send_mail($_POST['email'], "RE: Your comment in " . Lib::get_domain(HOST_URL), $_POST['response'])) {
                        $json_out['save_code'] = 1;
                        $json_out['save_txt'] = L::alert_message_sent_to . "{$_POST['email']}";
                    } else {
                        $json_out['save_code'] = 0;
                        $json_out['save_txt'] = L::alert_message_not_sent;
                    }
                }
                echo json_encode($json_out);
                exit;
            }

            $this->view->disable();
            //-- approving
            if (isset($_GET['approve'])) {
                $approveC = 0;
                $id = intval(@$_POST['id']);
                if ($model->select('status')->where(array($pk => $id))->exec()->current()->status != 1)
                    $approveC = $model->update(array('status' => 2, 'reviewed' => 1))->where(array($pk => $id))->exec();
                if ($approveC)
                    echo "{$approveC} " . L::alert_records_approve;
                else
                    echo "<span style='color:red'>" . L::alert_record_already_approved . "</span>";
                exit;
            }
            //-- disapproving
            if (isset($_GET['notapprove'])) {
                $approveC = 0;
                $id = intval(@$_POST['id']);
                if ($model->select('status')->where(array($pk => $id))->exec()->current()->status != -1)
                    $approveC = $model->update(array('status' => -1))->where(array($pk => $id))->exec();
                if ($approveC)
                    echo "<span style='color:green'>" . $approveC . " " . L::alert_records_disapprove . "</span>";
                else
                    echo "<span style='color:red'>" . L::alert_records_already_disapproved . "</span>";
                exit;
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
            //-- multi Approve
            if (isset($_GET['mapprove'])) {
                $approveC = 0;
                $ides = join(',', $_POST['id']);
                $approveC = $model->update(array('status' => 2, 'reviewed' => 1))->where($pk . " in ({$ides})")->exec();

                if ($approveC)
                    echo "{$approveC} " . L::alert_records_approve;
                else
                    echo "<span style='color:red'>" . L::alert_record_already_approved . "</span>";
                exit;
            }
            //-- multi Approve
            if (isset($_GET['mdisapprove'])) {
                $approveC = 0;
                $ides = join(',', $_POST['id']);
                $approveC = $model->update(array('status' => -1))->where($pk . " in ({$ides})")->exec();

                if ($approveC)
                    echo "{$approveC} " . L::alert_records_disapprove;
                else
                    echo "<span style='color:red'>" . L::alert_records_already_disapproved . "</span>";
                exit;
            }
            //-- Data Table
            if (isset($_GET['dt'])) {
                pengu_user_load_class('ab_bbcode', $bbcodeI);
                $aColumns = array(
                    'C.id', 'G.game_img', 'G.game_name', 'M.username', "DATE_FORMAT(FROM_UNIXTIME(time),'%Y-%m-%d %H:%i:%s')", 'comment', 'C.status', 'reviewed');
                $model->alias('C')
                    ->select(implode(',', $aColumns))->innerjoin('abs_games', 'G')->on('C.group=G.gid')
                    ->leftjoin('abs_members', 'M')->on('M.id=C.user_id');
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
                        if ($aColumns[$f] == 'G.game_img') {
                            //== game image
                            $img = '';
                            if (!empty($row[1]) && file_exists(ab_game_images_dir . '/' . $row[1]))
                                $img = $row[1];
                            $imageht = "<img  src=\"" . ab_game_create_img($img, 50, null) . "\" data-fullimage=\"" . ab_game_get_image_url($img) . "\"  rel=\"clbox\" >";
                            $row[1] = '<div class="thumbnail">' .
                                "<a  class='hint--" . (lang_isrtl() ? 'left' : 'right') . "' data-hint='{$row[2]}' >" .
                                $imageht .
                                "</a>" .
                                '</div>';
                        }

                        if ($aColumns[$f] == 'G.game_name') {
                            $v = '<a href="#">' . $v . '</a>';
                        }
                        if ($aColumns[$f] == 'comment') {
                            $v = str::summarize($v, 150);
                            $v = $bbcodeI->bbcode_decode($v);
                        }


                        if ($aColumns[$f] == 'C.status') {
                            if (intval($v) <= 0)
                                $iconshow = "<a  style='' href='#' title='" . L::global_approve . "' class='sepV_a'><i class='icon-check approve'></i></a>";
                            else if ($v >= 1)
                                $iconshow = "<a  style='' href='#' title='" . L::global_disapprove . "' class='sepV_a'><i class='icon-ban-circle disapprove'></i></a>";


                            if ($v == 0)
                                $v = "<span style='color:#0070FF'>" . L::global_pending . "</span>";
                            elseif ($v == 1)
                                $v = "<span style='color:#B6AD97'>" . L::forms_viewed . "</span>";
                            elseif ($v == 2)
                                $v = "<span style='color:green'>" . L::global_approved . "</span>";
                            elseif ($v == -1)
                                $v = "<span style='color:red'>" . L::global_disapproved . "</span>";
                        }


                        if ($aColumns[$f] == 'reviewed') {
                            if ($v)
                                $v = "<i class='splashy-mail_light_stuffed'></i>";
                            else
                                $v = "<i class='splashy-mail_light_down'></i>";
                        }
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>" .
                        "<a href='#' title='" . L::forms_show . "' class='sepV_a'><i class='icon-eye-open observe'></i></a>" . $iconshow;
                }


                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }
    }

    function games_import()
    {
        set_time_limit(60 * 60);
        $this->islogin();
        /* Grab func */

        function grab($url, $newname, &$msg)
        {
            if (!function_exists('curl_init'))
                return false;
            $ext = path::get_extension($url);
            if (in_array($ext, array('swf', 'dcr', 'unity3d')))
                $filetype = 2;
            else
                $filetype = 1;
            if (!preg_match(($filetype == 1 ? "/jpg|jpeg|gif|png|bmp/i" : "/swf|dcr|unity3d/i"), $ext)) {
                $msg = json_encode(array('type' => 'st-error', 'msg' => "File type is not accepted for game's image"));
                return false;
            } else {
                $filename = convert::filesafe($newname);
                $p = $filename . '.' . $ext;
                $i = 1;
                while (file_exists(($filetype == 1 ? ab_game_images_dir : ab_game_files_dir) . '/' . $p)) {
                    $p = $filename . '_' . $i . '.' . $ext;
                    $i++;
                }
                $filep = ($filetype == 1 ? ab_game_images_dir : ab_game_files_dir) . '/' . $p;
                if ($handle = fopen($filep, 'w')) {
                    if (function_exists('curl_init')) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_FILE, $handle);
                        if (curl_exec($ch))
                            $msg = json_encode(array('type' => 'st-success', 'msg' => "Game's " . ($filetype == 1 ? 'image' : 'file') . " was uploaded"));
                        curl_close($ch);
                        fclose($handle);
                    } else {
                        if ($data = path::file_get_contents_fopen($url)) {
                            fwrite($handle, $data);
                            $msg = json_encode(array('type' => 'st-success', 'msg' => "Game's " . ($filetype == 1 ? 'image' : 'file') . " was uploaded"));
                        }
                    }
                    permup($filep);
                    return $p;
                }
            }
        }

        if (isset($_GET['install'])) {
            if ($_POST['type'] == 'mobile') {
                /* ==== mobile ==== */
                $shuffledays = isset($_POST['shuffledays']) ? $_POST['shuffledays'] : null;
                pengu_user_load_class('ws', $ws);
                $data = $ws->get_from_feed_by_ws('webservicesController.packgamesmob', array($_POST['id']));
                if (!empty($data) && is_array($data)) {
                    foreach ($data as &$game) {
                        /* make thumb */
                        if (!empty($game['thumbnail']))
                            $game['thumbnail'] = master_import_games_images_dir . path::leftSlashes($game['thumbnail']);
                    }


                    $model = new MobileGame();
                    $do = 0;
                    foreach ($data as $rec) {
                        if (MobileGame::check_duplicate(@$rec['name']))
                            continue;
                        $time = $shuffledays ? mt_rand(strtotime("- $shuffledays days"), time()) : time();
                        $save = array(
                            'game_name' => $rec['name'],
                            'seo_title' => convert::seoText($rec['name']),
                            'game_categories' => Category::getCatsIdByTags($rec['genres']),
                            'game_description' => $rec['description'],
                            'game_img' => $rec['thumbnail'],
                            'game_image_source' => 0,
                            'game_adddate' => $time,
                            'game_upddate' => $time,
                            'game_source' => 'import',
                            'game_source_id' => $rec['id'],
                            'game_is_active' => 1
                        );
                        $msg = null;
                        if (!empty($save['game_img'])) {
                            if ($newfilename = grab($save['game_img'], $save['seo_title'], $msg))
                                $save['game_img'] = $newfilename;
                        }
                        $res = $model->insert($save)->exec();
                        if ($res) {
                            $do++;
                        }
                    }
                } else {
                    exit('0');
                }
            } else {
                /* ==== desktop ==== */
                $shuffledays = isset($_POST['shuffledays']) ? $_POST['shuffledays'] : null;
                pengu_user_load_class('ws', $ws);
                $data = $ws->get_from_feed_by_ws('webservicesController.packgames', array($_POST['id']));
                if (!empty($data) && is_array($data)) {
                    foreach ($data as &$game) {
                        /* make flash */

                        if (!empty($game['flash_file']))
                            $game['flash_file'] = master_import_games_file_dir . path::leftSlashes($game['flash_file']);

                        /* make thumb */
                        $game['thumbnail'] = null;
                        $feed_thumb_size = _get_theme_setting('feed_thumb_size');
                        if (!empty($feed_thumb_size) && !empty($game['thumbnail_' . $feed_thumb_size])) {
                            $game['thumbnail'] = master_import_games_images_dir . path::leftSlashes($game['thumbnail_' . $feed_thumb_size]);
                        } else {
                            if (!empty($game['thumbnail_150x150']))
                                $game['thumbnail'] = master_import_games_images_dir . path::leftSlashes($game['thumbnail_150x150']);
                            elseif (!empty($game['thumbnail_100x100']))
                                $game['thumbnail'] = master_import_games_images_dir . path::leftSlashes($game['thumbnail_100x100']);
                        }
                    }


                    $model = new Game();
                    $do = 0;
                    shuffle($data);
                    foreach ($data as $rec) {
                        if (Game::check_duplicate(@$rec['name']))
                            continue;
                        $time = $shuffledays ? mt_rand(strtotime("-$shuffledays days"), time()) : time();
                        $save = array(
                            'game_name' => $rec['name'],
                            'seo_title' => convert::seoText($rec['name']),
                            'game_categories' => Category::getCatsIdByTags($rec['genres']),
                            'game_description' => $rec['description'],
                            'game_keywords' => $rec['tags'],
                            'game_instruction' => $rec['instruction'],
                            'game_controls' => $rec['controls'],
                            'game_img' => $rec['thumbnail'],
                            'game_file' => $rec['flash_file'],
                            'game_width' => $rec['width'],
                            'game_height' => $rec['height'],
                            'game_image_source' => 0,
                            'game_file_source' => 0,
                            'game_adddate' => $time,
                            'game_upddate' => $time,
                            'game_source' => 'import',
                            'game_source_id' => $rec['id'],
                            'game_is_active' => 1
                        );
                        $msg = null;
                        if (!empty($save['game_img'])) {
                            if ($newfilename = grab($save['game_img'], $save['seo_title'], $msg))
                                $save['game_img'] = $newfilename;
                        }
                        $msg = null;
                        if (!empty($save['game_file'])) {
                            if ($newfilename = grab($save['game_file'], $save['seo_title'], $msg))
                                $save['game_file'] = $newfilename;
                        }

                        $res = $model->insert($save)->exec();
                        if ($res) {
                            $do++;
                        }
                    }
                } else {
                    exit('0');
                }
            }
            $s = new pengu_setting();
            $s->setSettingName('packgames');
            $s->save('installed' . $_POST['id'], true);
            exit('1');
        }

        function getpacklistbyws()
        {
            pengu_user_load_class('ws', $ws);
            $result = $ws->get_from_feed_by_ws('webservicesController.packlist');
            if (!is_array($result) || empty($result))
                return false;
            foreach ($result as &$data)
                $data['icon'] = master_upload_url . '/' . $data['icon'];
            return $result;
        }

        $s = new pengu_cache(cache_path() . '/etc/ws', 'pack_');
        $s->setCacheKey('packlist');
        $s->expireTime(7 * 24 * 3600);
        if (!$data = $s->read()) {
            $data = getpacklistbyws();
            $s->write($data);
        }
        if (is_array($data) && !empty($data))
            $this->view->packlist = $data;
    }

}
