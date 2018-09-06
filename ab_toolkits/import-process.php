<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-process.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


include 'init.php';
set_time_limit(3600);
@ini_set('memory_limit', '2048M');
/* ----------------------- */
/* ----//SOME MODELS\\---- */

function check_duplicate($link, $gamename, $gid = null)
{
    $cond = array('seo_title' => convert::seoText($gamename));
    if ($gid > 0)
        $cond[] = "gid<>{$gid}";

    $link->settable('abs_games');
    if ($link->where($cond)->getcount() > 0)
        return true;
    return false;
}

function tag_to_id($link, $tag_name)
{
    static $tags;
    $seo_tag_name = convert::seoText($tag_name);
    $found = null;
    if (empty($tags)) {
        $link->settable('abs_games_tags');
        $data = $link->select()->exec();
        if ($link->found()) {
            while ($row = $data->fetch()) {
                $tags[$row->id] = array('seo' => $row->seo_name, 'name' => $row->name);
            }
        }
    }
    if (!empty($tags)) {
        foreach ($tags as $key => $value) {
            if ($value['seo'] == $seo_tag_name || ($value['name'] == $tag_name && empty($value['seo'])))
                $found = $key;
        }
    }
    if (!$found) {
        $link->settable('abs_games_tags');
        if ($link->insert(array('name' => $tag_name, 'seo_name' => $seo_tag_name))->exec()) {
            $found = $link->lastinsid();
            $tags[$found] = array('seo' => $seo_tag_name, 'name' => $tag_name);
        }
    }
    return $found;
}

function tags_to_ids($link, $tags_name, $delimiter = ',')
{
    $tags = explode($delimiter, $tags_name);
    $tagsid = array();
    if (is_array($tags))
        foreach ($tags as $tag) {
            $tg = trim($tag);
            if (is_numeric($tg))
                $tagsid[] = $tg;
            elseif (!empty($tg))
                $tagsid[] = tag_to_id($link, $tg);
        }
    return array_unique($tagsid);
}

function cat_to_id($link, $cat_name)
{
    static $cats;
    $seo_cat_name = convert::seoText($cat_name);
    $found = null;
    if (empty($cats)) {
        $link->settable('abs_categories');
        $data = $link->select()->exec();
        if ($link->found()) {
            while ($row = $data->fetch()) {
                $cats[$row->cid] = array('seo' => $row->seo_title, 'title' => $row->title);
            }
        }
    }
    if (!empty($cats))
        foreach ($cats as $key => $value) {
            if ($value['seo'] == $seo_cat_name || ($value['title'] == $cat_name && empty($value['seo'])))
                $found = $key;
        }
    if (!$found) {
        $link->settable('abs_categories');
        if ($link->insert(array(
            'title' => $cat_name,
            'seo_title' => $seo_cat_name,
            'featured' => 1,
            'is_active' => 1
        ))->exec()
        ) {
            $found = $link->lastinsid();
            $cats[$found] = array('seo' => $seo_cat_name, 'title' => $cat_name);
        }
    }
    return $found;
}

function cats_to_ids($link, $categories, $delimiter = ',')
{
    $cats = explode($delimiter, $categories);
    $catsid = array();
    if (is_array($cats))
        foreach ($cats as $cat) {
            $ct = trim($cat);
            if (is_numeric($ct))
                $catsid[] = $ct;
            elseif (!empty($ct))
                $catsid[] = cat_to_id($link, $ct);
        }
    return array_unique($catsid);
}

/* ----\\END MODELS//---- */
/* ---------------------- */
/* ---------------------- */

function status()
{
    $data = getErrors();
    $finish = 0;
    if (strpos($data, '<!--finish-->') !== false)
        $finish = 1;
    return json_encode(array('finish' => $finish, 'data' => $data));
}

if (isset($_GET['getst']))
    die(status());

cleanErrors();
infoError('Start converting...');

$error = 0;
/* Destination connection */
$mydb = new mydb();
if (!$mydb->ping()) {
    $error = 1;
    warningError("Cannot connect to \"destination database\" server ! <br>
    <div class='hint'>To connect to destination database edit <b>config / db.config.php</b> and enter the valid username and password</div>")->Id('import-configuration');
}
/* Source connection (that one script) */
if (!import_check_session('source_con'))
    exit('Set The connection!');
$dbsourceinfo = import_get_session('source_con');
if (is_array($dbsourceinfo)) {
    $db = new db(array_merge($dbsourceinfo, array(
        'persist' => false,
        'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
        'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
    )));
    if (!$db->ping()) {
        $error = 1;
        warningError("Cannot connect to the source database. Your connection information is wrong!")->Id('import-configuration');
    }
}
if (!$error) {
    $tables = import_get_session('tables');
    $fields = import_get_session('fields');
    /* abs_cats */
    if (!empty($fields['abs_categories'])) {
        $mydb->link();
        $db->link();
        $db->settable($tables['abs_categories']);
        $fabs = $fields['abs_categories'];
        $columns = ($febs ? '`' . fjoin('`,`', $fabs) . '`' : '*');

        $abcats = $db->select($columns)->exec();
        dbCheckError($db);
        if ($db->found()) {
            $affected['cats'] = 0;
            while ($abcats->fetch()) {
                $cat = $abcats->current;
                $data = array(
                    'cid' => @$cat[$fabs['cid']],
                    'title' => @$cat[$fabs['title']],
                    'seo_title' => !empty($cat[$fabs['seo_title']]) ? $cat[$fabs['seo_title']] : convert::seoText($cat[$fabs['title']]),
                    'meta_description' => @$cat[$fabs['meta_description']],
                    'meta_keywords' => @$cat[$fabs['meta_keywords']],
                    'is_active' => 1,
                );
                $mydb->settable('abs_categories');
                if ($res = $mydb->insert($data)->exec())
                    $affected['cats']++;
                elseif ($res === false)
                    warningError("Error in " . $mydb->gettable() . " table: " . $mydb->lasterror());
            }
            successError("{$affected['cats']} records were imported to abs_categories table.");
        }
        $db->free_result();
        $db->unlink();
        $mydb->free_result();
        $mydb->unlink();
    }
    /* abs_tags */
    if (!empty($fields['abs_games_tags'])) {
        $mydb->link();
        $db->link();
        $db->settable($tables['abs_games_tags']);
        $fabs = $fields['abs_games_tags'];
        $columns = ($febs ? '`' . fjoin('`,`', $fabs) . '`' : '*');
        $abtags = $db->select($columns)->exec();
        dbCheckError($db);
        if ($db->found()) {
            $affected['tags'] = 0;
            while ($abtags->fetch()) {
                $tag = $abtags->current;
                $data = array(
                    'id' => @$tag[$fabs['id']],
                    'name' => @$tag[$fabs['name']],
                    'seo_name' => !empty($tag[$fabs['seo_name']]) ? $tag[$fabs['seo_name']] : convert::seoText($tag[$fabs['name']]),
                );
                $mydb->settable('abs_games_tags');
                if ($res = $mydb->insert($data)->exec())
                    $affected['tags']++;
                elseif ($res === false)
                    warningError("Error in " . $mydb->gettable() . " table: " . $mydb->lasterror());
            }
            successError("{$affected['tags']} records were imported to abs_games_tags table.");
        }
        $db->free_result();
        $db->unlink();
        $mydb->free_result();
        $mydb->unlink();
    }
    /* abs_games */
    if (!empty($fields['abs_games'])) {
        $mydb->link();
        $db->link();
        $db->settable($tables['abs_games']);
        $fabs = $fields['abs_games'];
        $columns = ($febs ? '`' . fjoin('`,`', $fabs) . '`' : '*');
        $abgames = $db->select($columns)->exec();
        dbCheckError($db);
        if ($db->found()) {
            $affected['games'] = 0;
            while ($abgames->fetch()) {
                $game = $abgames->current;

                $data = array(
                    'gid' => @$game[$fabs['gid']],
                    'game_name' => @$game[$fabs['game_name']],
                    'seo_title' => !empty($game[$fabs['seo_title']]) ? $game[$fabs['seo_title']] : convert::seoText($game[$fabs['game_name']]),
                    'game_categories' => fjoin(',', cats_to_ids($mydb, @$game[$fabs['game_categories']])),
                    'game_description' => @$game[$fabs['game_description']],
                    'game_tags' => fjoin(',', tags_to_ids($mydb, $game[$fabs['game_tags']])),
                    'game_img' => @$game[$fabs['game_img']],
                    'featured_img' => @$game[$fabs['featured_img']],
                    'game_file' => @$game[$fabs['game_file']],
                    'game_width' => intval(@$game[$fabs['game_width']]),
                    'game_height' => intval(@$game[$fabs['game_height']]),
                    'game_adddate' => !is_numeric(@$game[$fabs['game_adddate']]) && !empty($game[$fabs['game_adddate']]) ? strtotime($game[$fabs['game_adddate']]) : $game[$fabs['game_adddate']],
                    'game_rating' => @$game[$fabs['game_rating']],
                    'game_votes' => intval(@$game[$fabs['game_votes']]),
                    'game_is_featured' => @convert::to_bool($game[$fabs['game_is_featured']]) ? 1 : 0,
                    'game_source' => 'convert',
                    'game_is_active' => @convert::to_bool($game[$fabs['game_is_active']]) ? 1 : 0,
                );

                if (check_duplicate($mydb, $data['seo_title']))
                    continue;
                /* --move images-- */

                if ($data['game_is_featured'] == 1) {
                    if (empty($data['featured_img'])) {
                        $data['featured_img'] = $data['game_img'];
                        $data['game_img'] = null;
                    }
                    list($type, $nn) = copy_gimage_to_mine($data['featured_img']);
                    $data['game_image_source'] = $type;
                    if ($nn)
                        $data['featured_img'] = $nn;
                } else if (!empty($data['game_img'])) {
                    list($type, $nn) = copy_gimage_to_mine($data['game_img']);
                    $data['game_image_source'] = $type;
                    if ($nn)
                        $data['game_img'] = $nn;
                }
                /* --move file-- */
                if (!empty($data['game_file'])) {
                    list($type, $nn) = copy_game_to_mine($data['game_file']);
                    $data['game_file_source'] = $type;
                    if ($nn)
                        $data['game_file'] = $nn;
                }


                $mydb->settable('abs_games');
                if ($res = $mydb->insert($data)->exec())
                    $affected['games']++;
                elseif ($res === false)
                    warningError("Error in " . $mydb->gettable() . " table: " . $mydb->lasterror());
            }
            successError("{$affected['games']} records were imported to abs_games table.");
        }
        $db->free_result();
        $db->unlink();
        $mydb->free_result();
        $mydb->unlink();
    }
    /* abs_links */
    if (!empty($fields['abs_links'])) {
        $mydb->link();
        $db->link();
        $db->settable($tables['abs_links']);
        $fabs = $fields['abs_links'];
        $columns = ($febs ? '`' . fjoin('`,`', $fabs) . '`' : '*');
        $ablinks = $db->select($columns)->exec();
        dbCheckError($db);
        if ($db->found()) {
            $affected['links'] = 0;
            while ($ablinks->fetch()) {
                $link = $ablinks->current;
                $data = array(
                    'id' => @$link[$fabs['id']],
                    'partner_title' => @$link[$fabs['partner_title']],
                    'partner_url' => @$link[$fabs['partner_url']],
                    'expire_date' => date("Y-m-d", strtotime('+1 years')),
                    'link_type' => 1,
                    'status' => @convert::to_bool(@$link[$fabs['status']]) ? 1 : 0,
                );
                $mydb->settable('abs_links');
                if ($res = $mydb->insert($data)->exec())
                    $affected['links']++;
                elseif ($res === false)
                    warningError("Error in " . $mydb->gettable() . " table: " . $mydb->lasterror());
            }
            successError("{$affected['links']} records were imported to abs_links table.");
        }
        $db->free_result();
        $db->unlink();
        $mydb->free_result();
        $mydb->unlink();
    }
    /* abs_members */
    if (!empty($fields['abs_members'])) {
        $mydb->link();
        $db->link();
        $db->settable($tables['abs_members']);
        $fabs = $fields['abs_members'];
        $columns = ($febs ? '`' . fjoin('`,`', $fabs) . '`' : '*');
        $abmembers = $db->select($columns)->exec();
        dbCheckError($db);
        if ($db->found()) {
            $affected['members'] = 0;
            while ($abmembers->fetch()) {
                $member = $abmembers->current;
                $data = array(
                    'id' => @$member[$fabs['id']],
                    'username' => @$member[$fabs['username']],
                    'password' => @$member[$fabs['password']],
                    'email' => @$member[$fabs['email']],
                    'name' => @$member[$fabs['name']],
                    'avatar' => @$member[$fabs['avatar']],
                    'group' => 2,
                    'status' => @convert::to_bool(@$member[$fabs['status']]) ? 1 : 0,
                );


                if (!empty($data['avatar']) && $newname = copy_avatar_to_mine($data['avatar'])) {
                    $data['avatar'] = $newname;
                }


                $mydb->settable('abs_members');
                if ($res = $mydb->insert($data)->exec())
                    $affected['members']++;
                elseif ($res === false)
                    warningError("Error in " . $mydb->gettable() . " table: " . $mydb->lasterror());
            }
            successError("{$affected['members']} records were imported to abs_members table.");
        }
        $db->free_result();
        $db->unlink();
        $mydb->free_result();
        $mydb->unlink();
    }

    @rrmdir(ROOT_PATH . '/tmp/cache', '*.*');
    infoError('Importing process was finished.');
    addErrors('<!--finish-->');
}
