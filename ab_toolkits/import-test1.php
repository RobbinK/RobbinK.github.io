<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: import-test1.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */

include 'init.php';
/* ----------------------- */
/* ----//SOME MODELS\\---- */

function check_duplicate($link, $gamename, $gid = null) {
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

function game_type_to_text($type) {
    switch ($type) {
        case 0:return '<font style="color:#00BE03">Local Game</font>';
            break;
        case 1:return '<font style="color:#00BE03">Local Game (Should be grabbed)</font>';
            break;
        case 2:return '<font style="color:#0093BE">Remote Iframe</font>';
            break;
        case 3:return '<font style="color:#2D00FC">Remote File</font>';
            break;
        case 4:return '<font style="color:#909">Embedded Code</font>';
            break;
        default:return '<font style="color:#FC0A00">Unknown</font>';
    }
}

function content_file_detection_2($file) {
    static $mineDomain;
    if (!isset($mineDomain))
        $mineDomain = lib::get_domain(ROOT_URL);

    list($type, $path) = content_file_detection($file);


    if ($type === 0) {
        if (!do_copy())
            return array(0, basename($file));
        //filepath 
        return array(0, $path);
    } elseif ($type == 2) {
        //Remote iFrame
        return array(2, $path);
    } elseif ($type == 3) {
        //Remote Game
        if (lib::get_domain($path) == $mineDomain) {
            if (!do_copy())
                return array(0, path::get_basename($file));
            else
                return array(1, $path);
        } else
            return array(3, $path);
    } elseif ($type == 4) {
        //code
        return array(4, $path);
    }
    return array(null, null);
}

/* ----\\END MODELS//---- */
/* ---------------------- */
/* ---------------------- */
$error = 0;
/* Destination connection */
$mydb = new mydb();
if (!$mydb->ping()) {
    $error = 1;
    warningError("Cannot connect to \"destination database\" server ! <br>
    <div class='hint'>To connect to destination database edit <b>config / db.config.php</b> and enter the valid username and password</div>")->Id('import-test1');
}

$dbinfo = import_get_session('source_con');
$Tsource = array();
if (is_array($dbinfo)) {
    $db = new db(array_merge($dbinfo, array(
                'persist' => false,
                'cachesPath' => ROOT_PATH . '/tmp/cache/mysql',
                'logsPath' => ROOT_PATH . '/tmp/logs/mysql'
    )));

    if ($db->ping()) {
        $type = import_get_session('type');
        if ($type == 'manual') {
            $tables = import_get_session('tables');
            $fields = import_get_session('fields');
        } else {
            if (!file_exists('profiles/' . $type))
                exit('this profile is not exists!');
            else
                require_once ('profiles/' . $type);
            $tables = custom_tables($db);
        }
    }
}
include_once 'header.php';
?>
<style>
    th{
        background-color: #E7EEFF;
        width:180px;
    }
    th{
        text-align: center;
    }
    td{
        background-color: #F5F5F5;
    }
    .arrow{
        font: 10px tahoma bold;
        color: #F60;
    }
    .box1{
        width: 400px;
        height: 400px;
        overflow-y: scroll;
        overflow-x: hidden;
        border: solid 1px #E2D8D8;
        border-radius: 6px;
        background-color: #FFF;
        margin-bottom: 13px;
    }
    .box1 table{
        margin-bottom: 20px;
    }
    .tablename{
        border-radius: 10px 10px 0 0;
        width: 361px;
        display: block;
        height: 20px; 
        background-color: #474D55;
        color: #FFF;
        padding: 6px 10px;
        font: 15px arial bold;
    }
</style>
<div class="heading breadCrumb">
    <?php
    include "import-breadcrump.php"
    ?> 
</div>

<div>

    <fieldset>
        <?= alert('import-test1') ?>
        Check your games file bellow this way will help you to find what games will detected incorrectly by convertor:<br>

        <?php
        if (is_object($tables['abs_games']))
            $abgames = $tables['abs_games'];
        else {
            $db->settable($tables['abs_games']);
            $abgames = $db->select()->exec();
            dbCheckError($db);
        }
        $out = array();
        $out_title = array(
            'name' => 'Game Name',
            'duplicated' => 'Duplicated',
            'file' => 'Game file Path',
            'file_type' => 'Game Type',
            'thumb' => 'Game Thumb Path',
        );
        while ($abgames->fetch()) {
            $game = $abgames->current;

            if (function_exists('custom_conversion_games'))
                $data = custom_conversion_games($mydb, $game, $db);
            else {
                $data = array();
                foreach ($fields['abs_games'] as $k => $v)
                    $data[$k] = $game[$v];
            }
            $_thumb = $data['game_img'];
            $_duplicated = (check_duplicate($mydb, $data['seo_title']) ? '<font color="color:red">duplicated</font>' : '<font color="color:green">-</font>');

            list($_type, $_file) = content_file_detection_2($data['game_file']);

            $out[] = array(
                'name' => $data['game_name'],
                'duplicated' => $_duplicated,
                'file' => $_file,
                'file_type' => game_type_to_text($_type),
                'thumb' => $_thumb
            );
        }
        ?>
        <div id="fb">
            <table cellpadding="5px" style="width:100%">
                <tr>
                    <th style="width: 20px">#</th>
                    <th>Info</th>
                </tr>
                <?php
                foreach ($out as $n => $data) {
                    ?><tr>
                        <td><?= $n + 1 ?></td>
                        <td>
                            <b>Game Name :</b>    <?= $data['name'] ?><br>
                            <b>Duplicated : </b><?= $data['duplicated'] ?><br>
                            <b>File : </b> <?= $data['file'] ?><br>
                            <b>File Type :</b>  <?= $data['file_type'] ?><br>
                            <b>Game Thumb Path :</b>   <?= $data['thumb'] ?>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
        <br>
        <button onclick="document.location.href = 'import-finish.php';"> Next </button>
    </fieldset>
</div>    
<script type="text/javascript">
    $(function () {
    });
</script>
<?php
include_once 'footer.php';
?>