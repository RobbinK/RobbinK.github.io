<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-process-s.php
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

$type = import_get_session('type');
if (!file_exists('profiles/' . $type))
    exit('this profile is not exists!');
else
    require_once('profiles/' . $type);

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
    $tables = custom_tables($db);
    dbCheckError($db);

    /* abs_cats */
    if (function_exists('custom_conversion_ctgs') && !empty($tables['abs_categories'])) {
        $mydb->link();
        $db->link();
        if (is_object($tables['abs_categories']))
            $abcats = $tables['abs_categories'];
        else {
            $db->settable($tables['abs_categories']);
            $abcats = $db->select()->exec();
            dbCheckError($db);
        }
        if ($abcats->found()) {
            $affected['cats'] = 0;
            while ($abcats->fetch()) {
                $cat = $abcats->current;
                $data = custom_conversion_ctgs($mydb, $cat, $db);
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
    if (function_exists('custom_conversion_tags') && !empty($tables['abs_games_tags'])) {
        $mydb->link();
        $db->link();
        if (is_object($tables['abs_games_tags']))
            $abtags = $tables['abs_games_tags'];
        else {
            $db->settable($tables['abs_games_tags']);
            $abtags = $db->select()->exec();
            dbCheckError($db);
        }
        if ($abtags->found()) {
            $affected['tags'] = 0;
            while ($abtags->fetch()) {
                $tag = $abtags->current;
                $data = custom_conversion_tags($mydb, $tag, $db);
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
    if (function_exists('custom_conversion_games') && !empty($tables['abs_games'])) {
        $mydb->link();
        $db->link();
        if (is_object($tables['abs_games']))
            $abgames = $tables['abs_games'];
        else {
            $db->settable($tables['abs_games']);
            $abgames = $db->select()->exec();
            dbCheckError($db);
        }
        if ($abgames->found()) {
            $affected['games'] = 0;
            while ($abgames->fetch()) {
                $game = $abgames->current;
                $data = custom_conversion_games($mydb, $game, $db);

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
                } elseif (!empty($data['game_img'])) {
                    list($type, $nn) = copy_gimage_to_mine($data['game_img']);
                    $data['game_image_source'] = $type;
                    if ($nn)
                        $data['game_img'] = $nn;
                }
                /* --move file-- */
                if (!empty($data['game_file'])) {
                    if (@$data['game_file_source'] == 4 && !empty($data['game_file'])) {
                        // Embaded code
                    } else {
                        list($type, $nn) = copy_game_to_mine($data['game_file']);
                        $data['game_file_source'] = $type;
                        if ($nn)
                            $data['game_file'] = $nn;
                    }
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
    if (function_exists('custom_conversion_links') && !empty($tables['abs_links'])) {
        $mydb->link();
        $db->link();
        if (is_object($tables['abs_links']))
            $ablinks = $tables['abs_links'];
        else {
            $db->settable($tables['abs_links']);
            $ablinks = $db->select()->exec();
            dbCheckError($db);
        }
        if ($ablinks->found()) {
            $affected['links'] = 0;
            while ($ablinks->fetch()) {
                $link = $ablinks->current;
                $data = custom_conversion_links($mydb, $link, $db);
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
    if (function_exists('custom_conversion_members') && !empty($tables['abs_members'])) {
        $mydb->link();
        $db->link();
        if (is_object($tables['abs_members']))
            $abmembers = $tables['abs_members'];
        else {
            $db->settable($tables['abs_members']);
            $abmembers = $db->select()->exec();
            dbCheckError($db);
        }
        if ($abmembers->found()) {
            $affected['members'] = 0;
            while ($abmembers->fetch()) {
                $member = $abmembers->current;
                $data = custom_conversion_members($mydb, $member, $db);

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