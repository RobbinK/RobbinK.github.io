<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: init.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


@session_start();
error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', 1);
if (!preg_match('/ab_toolkits$/', dirname(__FILE__)))
    exit('/ab_toolkits folder was not found!');


##############################################
if (!file_exists('../path.php'))
    die("Unable to continue! Put ab_toolkits folder in your ArcadeBooster script folder.");
require_once ('../path.php');
if (!defined('ROOT_PATH'))
    die("path.php file in your script folder is not valid!");
require_once (ROOT_PATH . '/core/_jp.php');


define('ab_content_dir', ROOT_PATH . '/content');
define('ab_content_url', ROOT_PATH . '/content');

define('ab_upload_dir', ab_content_dir . '/upload');
define('ab_upload_url', ab_content_url . '/upload');
if (!file_exists(ab_upload_dir))
    rmkdir(ab_upload_dir);

define('ab_tmp_dir', ab_content_dir . '/upload/tmp');
define('ab_tmp_url', ab_content_url . '/upload/tmp');

define('ab_game_files_dir', ab_content_dir . '/upload/games/files');
define('ab_game_files_url', ab_content_url . '/upload/games/files');
if (!file_exists(ab_game_files_dir))
    rmkdir(ab_game_files_dir);

define('ab_game_images_dir', ab_content_dir . '/upload/games/images');
define('ab_game_images_url', ab_content_url . '/upload/games/images');
if (!file_exists(ab_game_images_dir))
    rmkdir(ab_game_images_dir);

class mydb extends pengu_db {

    function __construct() {
        global $ConnectOptions;
        parent::__construct($ConnectOptions);
        parent::link();
    }

    function  findTable($name){
        $data = $this->query("SHOW TABLES like '".$name."'")->exec();
        $abs_users_table = null;
        if ($data && $data->found()) {
            $d = $data->current;
            return current($d);
        }
    }

}

class db extends pengu_db {

    function __construct($coption) {
        parent::__construct($coption);
        parent::link();
    }

    function  findTable($name){
        $data = $this->query("SHOW TABLES like '".$name."'")->exec();
        $abs_users_table = null;
        if ($data && $data->found()) {
            $d = $data->current;
            return current($d);
        }
    }
}

if (!function_exists('rightchar')) {

    function rightchar($ch, $str) {
        $trimed = rtrim($str, $ch);
        if (!empty($trimed))
            return $trimed . $ch;
    }

}

function getMySQLVersion() {
    $output = @mysql_get_client_info();
    if (empty($output))
        $output = @shell_exec('mysql -V');
    if (empty($output))
        return null;
    preg_match('@[0-9]+\.[0-9]+\.[0-9]+@', $output, $version);
    return $version[0];
}

function isUrl($subject) {
    $subject = trim($subject);
    $pattern = '@^(http:\/\/www\.|https:\/\/www\.|http:\/\/|https:\/\/|www\.)([a-z0-9]+([\-\.]{1}[a-z0-9]+)*\.[a-z]{2,5}|localhost|\d{0,3}\.\d{0,3}\.\d{0,3}\.\d{0,3})(:[0-9]{1,5})?(\/.*)?$@';
    return preg_match($pattern, $subject);
}

function fjoin($glue, $pieces) {
    if (!is_array($pieces) || empty($pieces))
        return null;
    $pieces = arrayUtil::array_filter_recursive($pieces);
    return @join($glue, $pieces);
}

function dpost($name, $default = null) {
    if (isset($_POST[$name]))
        return $_POST[$name];
    else
        return $default;
}

function getTables($dbobj) {
    $value = array();
    if (!($result = $dbobj->query('SHOW TABLES')->exec()))
        return false;
    while ($result->fetch()) {
        $t0 = array_values($result->current);
        if (!empty($t0[0])) {
            $value[] = $t0[0];
        }
    }
    if (!sizeof($value))
        return false;
    return $value;
}

function getFields($dbobj, $table) {
    $fields = array();
    if (!empty($table)) {
        $fres = $dbobj->query("SHOW COLUMNS FROM `{$table}`;")->exec()->allrows();
        foreach ($fres as $info)
            $fields[] = $info['Field'];
    }
    return $fields;
}

/* error functions */

function getErrors() {
    $er = null;
    if (!empty($_SESSION['errors']) && is_array($_SESSION['errors'])) {
        while ($e = @array_shift($_SESSION['errors']))
            $er.= $e;
    }
    return $er;
}

function addErrors($text) {
    $_SESSION['errors'][] = $text;
}

function cleanErrors() {
    $_SESSION['errors'] = array();
}

function warningError($msg) {
    if (!empty($msg))
        addErrors("<font style='color:#945300'>" . $msg . '</font><br>');
}

function successError($msg) {
    if (!empty($msg))
        addErrors("<font style='display: block;color: #41510B;background: #BAEF00;border: solid 1px #8BBE17;border-radius: 7px;padding: 3px'>" . $msg . '</font>');
}

function infoError($msg) {
    if (!empty($msg))
        addErrors("<font style='display: block;color: #fff;background: #17A0D0;border: solid 1px #1488B0;border-radius: 7px;padding: 3px;'>" . $msg . '</font>');
}

function dbCheckError($dbobj) {
    $er = null;
    if (@$dbobj->lasterror()) {
        $er.= "<div class='mysqlerror'>";
        $er.= "<font style='color:#E90000'>" . $dbobj->lasterror() . '</font><br>';
        $er.= "<font style='color:#188DBB'>" . $dbobj->lastsql() . '</font><br>';
        $er.= "</div>";
    }
    addErrors($er);
}

function crached() {
    addErrors('<!--finish-->');
}

/* ------------- */

function activep($act = null) {
    $url = url::itself()->url_nonqry();
    if (!empty($act))
        return strpos(basename($url), $act) !== false;
    else
        return $_GET['act'];
}

function import_check_session($key) {
    if (isset($_SESSION['import'][$key]) && !empty($_SESSION['import'][$key]))
        return true;
}

function import_get_session($key) {
    $map = array(
        'type' => 'import-type.php',
        'source_con' => 'import-configuration.php',
        'tables' => 'import-settables.php',
        'fields' => 'import-setfields.php'
    );
    if (isset($_SESSION['import'][$key]))
        return $_SESSION['import'][$key];
    else if (isset($map[$key]) && !headers_sent()) {
        header("location: {$map[$key]}");
        exit;
    }
}

function import_get_paths() {
    if (function_exists('custom_paths'))
        return custom_paths();
    else if (import_check_session('paths'))
        return import_get_session('paths');
}

function import_set_session($key, $val) {
    $_SESSION['import'][$key] = $val;
}

function is_url_exist($url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}

function get_url_mime_type($url) {
    if (!function_exists('curl_init'))
        return false;
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_exec($ch);
    $out = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
    curl_close($ch);
    return $out;
}

function get_url_ext($url) {
    $ext = array(
        "pdf" => "application/pdf"
        , "exe" => "application/octet-stream"
        , "zip" => "application/zip"
        , "docx" => "application/msword"
        , "doc" => "application/msword"
        , "xls" => "application/vnd.ms-excel"
        , "ppt" => "application/vnd.ms-powerpoint"
        , "gif" => "image/gif"
        , "png" => "image/png"
        , "jpeg" => "image/jpg"
        , "jpg" => "image/jpg"
        , "mp3" => "audio/mpeg"
        , "wav" => "audio/x-wav"
        , "mpeg" => "video/mpeg"
        , "mpg" => "video/mpeg"
        , "mpe" => "video/mpeg"
        , "mov" => "video/quicktime"
        , "avi" => "video/x-msvideo"
        , "3gp" => "video/3gpp"
        , "css" => "text/css"
        , "jsc" => "application/javascript"
        , "js" => "application/javascript"
        , "php" => "text/html"
        , "htm" => "text/html"
        , "html" => "text/html"
    );
    $ext = array_flip($ext);

    if ($mt = get_url_mime_type($url)) {
        return isset($ext[$mt]) ? $ext[$mt] : null;
    }
}

function content_file_detection($content) {
    $content=trim($content);
    if (!preg_match('/[^A-Za-z0-9\s_\.\/\-]/', $content)) {
        $path = import_get_paths();

        //filepath
        if (file_exists($content)) {
            return array(0, $content);
        } elseif (!empty($path['files']) && file_exists($path['files'] . '/' . $content) && !empty($content)) {
            return array(0, $path['files'] . '/' . $content);
        } elseif (!empty($path['files']) && file_exists($path['files'] . '/' . basename($content)) && !empty($content)) {
            return array(0, $path['files'] . '/' . basename($content));
        }
    } elseif (isUrl($content)) {
        $files = array('swf', 'dcr', 'unity3d');
        if (in_array(path::get_extension($content), $files)) {
            //Remote Game File Link (swf, unity3d, dcr)
            return array(3, $content);
        } elseif (in_array(get_url_ext($content), $files)) {
            //Remote Game File Link (swf, unity3d, dcr)
            return array(3, $content);
        } else {
            //Remote iFrame Link (HTML5, swf, unity3d,dcr,…)
            return array(2, $content);
        }
    } else {
        //code
        return array(4, $content);
    }
}

function get_newname($path, $filenamebody, $ext) {
    $p = $filenamebody . '.' . $ext;
    $i = 1;
    while (file_exists($path . '/' . $p)) {
        $p = $filenamebody . '_' . $i . '.' . $ext;
        $i++;
    }
    return $p;
}

/* Grab Function */

function grab($url, $path, $newname) {
    set_time_limit(10 * 60);
    $ext = path::get_extension($url);
    $p = get_newname($path, $newname, $ext);
    $filep = $path . '/' . $p;
    $handle = fopen($filep, 'w');
    $ret = false;
    $grabbed = false;
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FILE, $handle);
        if (curl_exec($ch)) {
            $ret = $p;
            $grabbed = true;
        }
        curl_close($ch);
        fclose($handle);
    } else {
        if ($data = path::file_get_contents_fopen($url)) {
            fwrite($handle, $data);
            $ret = $p;
            $grabbed = true;
        }
    }
    permup($filep);
    if (!$grabbed)
        @unlink($filep);
    return $ret;
}

function do_copy() {
    if (import_check_session('file_action')) {
        $act = import_get_session('file_action');
        if ($act == 'notcopy')
            return false;
    }
    return true;
}

function copy_avatar_to_mine($image, $newname = null) {
    $path = import_get_paths();
    if (import_check_session('file_action')) {
        $act = import_get_session('file_action');
        if ($act == 'notcopy')
            return basename($image);
    }

    if (!empty($newname))
        $newname = convert::filesafe($newname);
    else
        $newname = path::get_filename($image);


    if (!preg_match('/[^A-Za-z0-9\s_\.\/\-]/', $image)) {
        //filepath 
        if (!do_copy())
            return array(0, basename($image));
        $imagepath = null;
        if (file_exists($image)) {
            $imagepath = $image;
        } elseif (!empty($path['avatars']) && file_exists($path['avatars'] . '/' . $image) && !empty($content)) {
            $imagepath = $path['avatars'] . '/' . $image;
        } elseif (!empty($path['avatars']) && file_exists($path['avatars'] . '/' . basename($image)) && strlen(basename($image)) > 0) {
            $imagepath = $path['avatars'] . '/' . basename($image);
        }

        $ext = path::get_extension($imagepath);
        $nn = get_newname(ab_upload_dir, $newname, $ext);
        $dest = ab_upload_dir . '/' . $nn;
        if ($imagepath && copy($imagepath, $dest))
            return $nn;
    } elseif (isUrl($image)) {
        if (!do_copy())
            return array(0, path::get_basename($image));
        else
            return grab($image, ab_upload_dir, $newname);
    }
    return null;
}

function copy_gimage_to_mine($image, $newname = null) {
    $path = import_get_paths();

    if (!empty($newname))
        $newname = convert::filesafe($newname);
    else
        $newname = path::get_filename($image);

    if (!preg_match('/[^A-Za-z0-9\s_\.\/\-]/', $image)) {
        //filepath 
        if (!do_copy())
            return array(0, basename($image));

        $imagepath = null;
        if (file_exists($image)) {
            $imagepath = $image;
        } elseif (!empty($path['thumbs']) && file_exists($path['thumbs'] . '/' . $image) && !empty($content)) {
            $imagepath = $path['thumbs'] . '/' . $image;
        } elseif (!empty($path['thumbs']) && file_exists($path['thumbs'] . '/' . basename($image)) && strlen(basename($image)) > 0) {
            $imagepath = $path['thumbs'] . '/' . basename($image);
        }

        $ext = path::get_extension($imagepath);
        $nn = get_newname(ab_game_images_dir, $newname, $ext);
        $dest = ab_game_images_dir . '/' . $nn;
        if ($imagepath && copy($imagepath, $dest))
            return array(0, $nn);
    } elseif (isUrl($image)) {
        if (!do_copy())
            return array(0, path::get_basename($image));
        else
            return array(1, grab($image, ab_game_images_dir, $newname));
    }
    return array(null, null);
}

function copy_game_to_mine($file, $newname = null) {
    static $mineDomain;
    if (!isset($mineDomain))
        $mineDomain = lib::get_domain(ROOT_URL);

    list($type, $path) = content_file_detection($file);

    if (!empty($newname))
        $newname = convert::filesafe($newname);
    else
        $newname = path::get_filename($path);

    if ($type === 0) {
        if (!do_copy())
            return array(0, basename($path));
        //filepath
        $ext = path::get_extension($path);
        $nn = get_newname(ab_game_files_dir, $newname, $ext);
        $dest = ab_game_files_dir . '/' . $nn;
        if ($path && copy($path, $dest))
            return array(0, $nn);
    } elseif ($type == 2) {
        //Remote iFrame
        return array(2, $path);
    } elseif ($type == 3) {
        //Remote Game
        if (lib::get_domain($path) == $mineDomain) {
            if (!do_copy())
                return array(0, path::get_basename($path));
            else
                return array(1, grab($path, ab_game_files_dir, $newname));
        }
        else
            return array(3, $path);
    } elseif ($type == 4) {
        //code
        return array(4, $path);
    }
    return array(null, null);
}


