<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_update_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:59
##########################################################
 */


function ftp_is_dir($ftpcon, $dir) {
    $original_directory = @ftp_pwd($ftpcon);
    if (@ftp_chdir($ftpcon, $dir)) {
        ftp_chdir($ftpcon, $original_directory);
        return true;
    } else {
        return false;
    }
}

function checkFtpConnection($server, $username, $password) {
    if (!function_exists('ftp_connect')) {
        warning("Your server does not support ftp_connect() function or some other FTP statements!");
        return false;
    }
    $con = @ftp_connect($server) or $ret = 0;
    if (isset($ret) && $ret == 0)
        return false;

    try {
        if (!@ftp_login($con, $username, $password))
            return false;
    } catch (Exception $e) {
        echo "showST('{$e}');";
    }
    return $con;
}

function ftpUpdateFile($dest_file, $data) {
    $server = Setting::get_data('ftp_host', 'val');
    $username = Setting::get_data('ftp_username', 'val');
    $password = Setting::get_data('ftp_password', 'val');
    $ret = false;
    $conid = checkFtpConnection($server, $username, $password);
    if ($conid) {
        //create tmp file
        $tmpfile = cache_path() . '/etc/ftp_' . lib::rand() . '.dat';
        $fhandle = fopen($tmpfile, 'w+');
        fwrite($fhandle, $data);
        fclose($fhandle);

        if (ftp_put($conid, $dest_file, $tmpfile, FTP_BINARY))
            $ret = true;

        ftp_close($conid);
    }
    return $ret;
}

function ws_available_version() {
    static $result;
    if (isset($result))
        return $result;
    pengu_user_load_class('ws', $ws);
    $result = $ws->get_from_feed_by_ws('webservicesController.get_available_version');
    if (empty($result))
        return false;
    return $result;
}

function isEmptyDir($dir) {
    return !file_exists($dir) || (($files = @scandir($dir)) && count($files) <= 2);
}
