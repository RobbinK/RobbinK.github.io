<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: Admin_feeds_controller.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:57
##########################################################
 */


class Admin_feedsController extends AdministrationController
{

    protected $_model = null;

    function __construct()
    {
        parent::__construct();
        $this->MapViewFile_groupFolder('vg_admin_feeds');
    }


    function revenue_games()
    {
        $this->islogin();
        $this->MapViewFileName('revenue_games.php');

        function syncFeeds($force = false)
        {
            /*  CAll WebService  */
            if ($force || (!isset($_COOKIE['synced_ws_revfeeds']) && convert::to_bool(setting::get_data('feed_auto_downloader', 'val')))) {
                setcookie('synced_ws_revfeeds', 1, time() + 12 * 3600, '/');
                Setting::delete_all_cache();
                pengu_user_load_class('ws', $ws);
                $result = $ws->get_from_feed_by_ws('webservicesController.get_latest_revinue_sharing_games', array(100, setting::get_data('last_revfeed_time', 'val')));
                if (!is_array($result) || empty($result))
                    return false;
                $model = new Game_feed();
                foreach ($result as $key => $val) {
                    $model->insert(array(
                        'fid' => $val['fid'],
                        'fsource' => $val['fsource'],
                        'name' => $val['name'],
                        'seoname' => $val['seoname'],
                        'flash_file' => $val['flash_file'],
                        'thumbnail_100x100' => $val['thumbnail_100x100'],
                        'thumbnail_150x150' => $val['thumbnail_150x150'],
                        'thumbnail_180x135' => $val['thumbnail_180x135'],
                        'thumbnail_90x120' => $val['thumbnail_90x120'],
                        'thumbnail_hex' => $val['thumbnail_hex'],
                        'full_disc' => $val['full_disc'],
                        'short_disc' => $val['short_disc'],
                        'instruction' => $val['instruction'],
                        'controls' => @$val['controls'],
                        'tags' => $val['tags'],
                        'genres' => $val['genres'],
                        'width' => $val['width'],
                        'height' => $val['height'],
                        'revenue_sharing' => 1,
                        'create_time' => $val['create_time'],
                        'create_date' => $val['create_date'],
                        'insert_date' => date('Y-m-d'),
                        'status' => 0
                    ))->exec();
                }
                /* save last time */
                end($result);
                if ($last = current($result))
                    Setting::save_value('status', 'last_revfeed_time', $last['create_time']);
            }
        }

        syncFeeds(isset($_GET['forcesync']) ? true : false);
        $model = new Game_feed();


        if (validate::_is_ajax_request()) {
            $this->view->disable();
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
            //-- install
            if (isset($_GET['install'])) {
                $id = input::post('id');
                if ($data = $model->select()->where(array('fid' => $id))->exec()->current) {
                    $st = $this->installfeed($data, $_POST['active']);
                    if ($st === false)
                        echo 0;
                    elseif ($st === -1)
                        echo -1;
                    else
                        echo 1;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $thumbc = "concat(ifnull(thumbnail_100x100,''),'|',ifnull(thumbnail_150x150,''),'|',ifnull(thumbnail_180x135,''),'|',ifnull(thumbnail_90x120,''),'|',ifnull(thumbnail_hex,''))";
                $aColumns = array('id', 'fid', $thumbc, 'name', 'genres', 'insert_date', 'has_ads', 'status');
                $model->select(join(',', $aColumns));
                $cond = array();
                if (isset($_GET['cat']) && $_GET['cat'] != 'all')
                    $cond[] = "concat(',',genres,',') like ('%," . input::get('cat') . ",%')";
                $cond[] = "ifnull(revenue_sharing,0)=1";
                $model->where($cond);
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();


                foreach ($myrows as &$row) {
                    $insIcon = null;

                    foreach ($row as $f => &$v) {
                        if ($f == 2) {
                            $th = array();
                            list($th['thumbnail_100x100'], $th['thumbnail_150x150'], $th['thumbnail_180x135'], $th['thumbnail_90x120'], $th['thumbnail_hex']) = explode('|', $v);
                            $this->revfeed_prepare_thumbs($th);

                            $v = '<div class="thumbnail">'
                                . "<img src=\"" . $th['thumbnail'] . "\"  rel='clbox'/>" .
                                '</div>';
                        }
                        if ($aColumns[$f] == 'status')
                            switch ($v) {
                                case 2 :
                                    $v = "<span class='text-success'>" . L::global_installed . "</span>";
                                    break;
                                default:
                                    $v = "<span class='text-gray'>-</span>";
                                    $insIcon = "<a href='#' title='" . L::forms_install . "' class='sepV_a'><i class='icon-download ins'></i></a>";
                                    break;
                            }
                        if ($aColumns[$f] == 'genres')
                            $v = str::summarize($v, 30);

                        if ($aColumns[$f] == 'has_ads')
                            $v = (!convert::to_bool($v) ? '<div style="padding:7px"><i class="splashy-box_okay"></i> ' . L::global_state_no . '</div>' : '<div style="padding:7px"><i class="splashy-box_warning"></i> ' . L::global_state_yes . '</div>');
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<input type='hidden' class='row_fid' value='{$row[1]}' />" .
                        $insIcon .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }


        /* some vars */
        $this->view->cats = setting::get_data('feed_categories', 'val');
        $this->view->sources = setting::get_data('feed_source', 'val');
    }

    function latest_feed()
    {
        $this->islogin();
        $this->MapViewFileName('latest_feeds.php');

        function syncFeeds($force = false)
        {
            /*  CAll WebService  */
            if ($force || (!isset($_COOKIE['synced_ws_feeds']) && convert::to_bool(setting::get_data('feed_auto_downloader', 'val')))) {
                setcookie('synced_ws_feeds', 1, time() + 12 * 3600, '/');
                Setting::delete_all_cache();
                pengu_user_load_class('ws', $ws);
                $result = $ws->get_from_feed_by_ws('webservicesController.get_latest_feed_games', array(setting::get_data('last_feed_time', 'val')));
                if (!is_array($result) || empty($result))
                    return false;
                $model = new Game_feed();
                foreach ($result as $key => $val) {
                    $model->insert(array(
                        'fid' => $val['fid'],
                        'fsource' => $val['fsource'],
                        'name' => $val['name'],
                        'seoname' => $val['seoname'],
                        'flash_file' => $val['flash_file'],
                        'thumbnail_100x100' => $val['thumbnail_100x100'],
                        'thumbnail_150x150' => $val['thumbnail_150x150'],
                        'thumbnail_180x135' => $val['thumbnail_180x135'],
                        'thumbnail_90x120' => $val['thumbnail_90x120'],
                        'thumbnail_hex' => $val['thumbnail_hex'],
                        'full_disc' => $val['full_disc'],
                        'short_disc' => $val['short_disc'],
                        'instruction' => $val['instruction'],
                        'controls' => @$val['controls'],
                        'tags' => $val['tags'],
                        'genres' => $val['genres'],
                        'width' => $val['width'],
                        'height' => $val['height'],
                        'has_ads' => $val['has_ads'],
                        'revenue_sharing' => $val['revenue_sharing'],
                        'create_time' => $val['create_time'],
                        'create_date' => $val['create_date'],
                        'insert_date' => date('Y-m-d'),
                        'status' => 0
                    ))->exec();
                }
                /* save last time */
                end($result);
                if ($last = current($result))
                    Setting::save_value('status', 'last_feed_time', $last['create_time']);
            }
        }

        syncFeeds(isset($_GET['forcesync']) ? true : false);
        $model = new Game_feed();


        if (validate::_is_ajax_request()) {
            $this->view->disable();
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
            //-- install
            if (isset($_GET['install'])) {
                $id = input::post('id');
                if ($data = $model->select()->where(array('fid' => $id))->exec()->current) {
                    $st = $this->installfeed($data, $_POST['active']);
                    if ($st === false)
                        echo 0;
                    elseif ($st === -1)
                        echo -1;
                    else
                        echo 1;
                }
                exit;
            }

            //-- Data Table
            if (isset($_GET['dt'])) {
                $thumbc = "concat(ifnull(thumbnail_100x100,''),'|',ifnull(thumbnail_150x150,''),'|',ifnull(thumbnail_180x135,''),'|',ifnull(thumbnail_90x120,''),'|',ifnull(thumbnail_hex,''))";
                $aColumns = array('id', 'fid', $thumbc, 'name', 'genres', 'insert_date', 'has_ads', 'status');
                $model->select(join(',', $aColumns));
                $cond = array();
                if (isset($_GET['source']) && $_GET['source'] != 'all')
                    $cond[] = "concat(',',fsource,',') like ('%," . input::get('source') . ",%')";
                if (isset($_GET['cat']) && $_GET['cat'] != 'all')
                    $cond[] = "concat(',',genres,',') like ('%," . input::get('cat') . ",%')";
                if (isset($_GET['withoutad']) && convert::to_bool($_GET['withoutad']))
                    $cond[] = "ifnull(has_ads,0)=0";
                $cond[] = "ifnull(revenue_sharing,0)=0";
                $model->where($cond);
                pengu_user_load_class('ab_jdatatable', $jdt, $model);
                $jdt->setColumns($aColumns);
                $jdt->preparePagin();
                $jdt->prepareOrdering();
                $jdt->prepareFiltering();
                $jdt->process();
                $myrows = $jdt->getData();


                foreach ($myrows as &$row) {
                    $insIcon = null;

                    foreach ($row as $f => &$v) {
                        if ($f == 2) {
                            $th = array();
                            list($th['thumbnail_100x100'], $th['thumbnail_150x150'], $th['thumbnail_180x135'], $th['thumbnail_90x120'], $th['thumbnail_hex']) = explode('|', $v);
                            $this->feed_prepare_thumbs($th);

                            $v = '<div class="thumbnail">'
                                . "<img src=\"" . $th['thumbnail'] . "\"  rel='clbox'/>" .
                                '</div>';
                        }
                        if ($aColumns[$f] == 'status')
                            switch ($v) {
                                case 2 :
                                    $v = "<span class='text-success'>" . L::global_installed . "</span>";
                                    break;
                                default:
                                    $v = "<span class='text-gray'>-</span>";
                                    $insIcon = "<a href='#' title='" . L::forms_install . "' class='sepV_a'><i class='icon-download ins'></i></a>";
                                    break;
                            }
                        if ($aColumns[$f] == 'genres')
                            $v = str::summarize($v, 30);

                        if ($aColumns[$f] == 'has_ads')
                            $v = (!convert::to_bool($v) ? '<div style="padding:7px"><i class="splashy-box_okay"></i> ' . L::global_state_no . '</div>' : '<div style="padding:7px"><i class="splashy-box_warning"></i> ' . L::global_state_yes . '</div>');
                    }
                    $row[] = "<input type='hidden' class='row_id' value='{$row[0]}' />" .
                        "<input type='hidden' class='row_fid' value='{$row[1]}' />" .
                        $insIcon .
                        "<a href='#' title='" . L::global_remove . "' class='sepV_a'><i class='icon-trash del'></i></a>";
                }

                $jdt->setData($myrows);
                echo $jdt->renderOutput();
                exit;
            }
        }


        /* some vars */
        $this->view->cats = setting::get_data('feed_categories', 'val');
        $this->view->sources = setting::get_data('feed_source', 'val');
    }

    function installfeed($data, $active = -1)
    {
        if (Game::check_duplicate(@$data['name'])) {
            /* set feed status to already install */
            $model = new Game_feed;
            $model->update(array('status' => 2))->where(array('fid' => @$data['fid']))->exec();
            return -1;
        }

        $isRevShare = (isset($data['revenue_sharing']) && $data['revenue_sharing'] == 1);

        /* set file */
        if (empty($data['file']))
            if ($isRevShare)
                $this->revfeed_prepare_file($data);
            else
                $this->feed_prepare_file($data);

        /* set thumbnail */
        if (empty($data['thumbnail']))
            if ($isRevShare)
                $this->revfeed_prepare_thumbs($data);
            else
                $this->feed_prepare_thumbs($data);

        $save = array(
            'game_name' => @$data['name'],
            'seo_title' => (!empty($data['seoname']) ? $data['seoname'] : convert::seoText($data['name'])),
            'game_categories' => Category::getCatsIdByTags($data['genres']),
            'game_description' => @$data['full_disc'],
            'game_keywords' => $data['tags'],
            'game_instruction' => @$data['instruction'],
            'game_controls' => @$data['controls'],
            'game_image_source' => 0,
            'game_file_source' => 0,
            'game_img' => @$data['thumbnail'],
            'game_file' => @$data['file'],
            'game_width' => @$data['width'],
            'game_height' => @$data['height'],
            'game_adddate' => time(),
            'game_upddate' => time(),
            'game_source' => ($isRevShare ? 'revenue_sharing' : 'feed'),
            'game_source_id' => @$data['fid'],
            'game_is_active' => $active,
        );

        /* Grab Function */

        function grab($url, $newname, &$msg)
        {
            set_time_limit(10 * 60);
            $ext = path::get_extension($url);
            if (in_array($ext, array('swf', 'dcr', 'unity3d')))
                $filetype = 2;
            else
                $filetype = 1;
            if (!preg_match(($filetype == 1 ? "/jpg|jpeg|gif|png/i" : "/swf|dcr|unity3d/i"), $ext)) {
                $msg = json_encode(array('type' => 'st-error', 'msg' => 'Invalid file format!'));
                return false;
            } else {
                $filename = ($filetype == 1 ? 'image_' : 'game_') . convert::filesafe($newname);
                $p = $filename . '.' . $ext;
                $i = 1;
                while (file_exists(($filetype == 1 ? ab_game_images_dir : ab_game_files_dir) . '/' . $p)) {
                    $p = $filename . '_' . $i . '.' . $ext;
                    $i++;
                }
                $filep = ($filetype == 1 ? ab_game_images_dir : ab_game_files_dir) . '/' . $p;
                $handle = fopen($filep, 'w');
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

        $model = new Game();
        if ($model->insert($save)->exec()) {
            $insid = $model->lastinsid();
            /* checked as installed for last 30 feed */
            $s = new pengu_cache(cache_path() . '/etc/ws', 'data_');
            $s->setCacheKey('daily_ws_data');
            $s->expireTime(12 * 3600);
            if ($s->isCached()) {
                $l30FeedData = $s->read();
                if (!empty($l30FeedData)) {
                    foreach ($l30FeedData as &$fd) {
                        if (@$fd['fid'] == $data['fid'])
                            $fd['installed'] = true;
                    }
                    $s->write($l30FeedData);
                }
            }
            /* set feed status to installed */
            $model = new Game_feed;
            $model->update(array('status' => 2))->where(array('fid' => @$data['fid']))->exec();

            // increase installed feed on arcadebooster
            pengu_user_load_class('ws', $ws);
            $ws->get_from_feed_by_ws('webservicesController.feed_installed', array($data['fid']));

            return $insid;
        } else {
            pengu_user_load_class('ws', $ws);
            $ws->get_from_feed_by_ws('webservicesController.feed_installing_failed', array($data['fid']));
            return false;
        }
    }

    function feed_open()
    {
        $this->islogin();
        $this->MapViewFileName('feed_open.php');
        $this->view->tmp_dir = ab_tmp_dir;
        $this->view->tmp_url = ab_tmp_url;

        /* Install a feed */
        if (validate::_is_ajax_request()) {
            if (isset($_GET['install'])) {
                $this->getPOST($_POST);

                if (Game::check_duplicate($_POST['name']))
                    exit(json_encode(array('type' => 'st-error', 'msg' => L::alert_game_exists)));

                $insid = $this->installfeed($_POST, $_POST['active']);
                if ($insid > 0) {
                    $this->cleanMysqlCache();
                    exit(json_encode(array('type' => 'st-success', 'msg' => L::alert_installed_success, 'insid' => $insid)));
                } else
                    exit(json_encode(array('type' => 'st-error', 'msg' => L::alert_installing_error)));
            }
        }


        /* ===== webservice function ===== */

        function getbyws2($id)
        {
            /*  CAll WebService  */
            pengu_user_load_class('ws', $ws);
            if (isset($_GET['revshare']))
                $result = $ws->get_from_feed_by_ws('webservicesController.get_rev_feed', array($id));
            else
                $result = $ws->get_from_feed_by_ws('webservicesController.get_feed', array($id));
            if (!is_array($result) || empty($result))
                return false;
            return $result;
        }

        $id = $_GET['id'];
        $model = new Game_feed();
        $res = $model->select()->where(array('fid' => $id))->exec();
        if ($res->found()) {
            $data = $res->current;
            if (isset($_GET['revshare'])) {
                $this->revfeed_prepare_file($data);
                $this->revfeed_prepare_thumbs($data);
            } else {
                $this->feed_prepare_file($data);
                $this->feed_prepare_thumbs($data);
            }
            $model->update(array('status' => 1))->where(array('fid' => $data['id'], 'status' => 0))->exec();
        } else {
            $data = getbyws2($id);
            if (isset($_GET['revshare'])) {
                $this->revfeed_prepare_file($data);
                $this->revfeed_prepare_thumbs($data);
            } else {
                $this->feed_prepare_file($data);
                $this->feed_prepare_thumbs($data);
            }
        }
        $this->view->data = $data;
    }

}
