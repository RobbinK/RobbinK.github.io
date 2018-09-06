<?php
/*
 ##########################################################
## This script is copyrighted to ArcadeBooster.com and you
## are free to modify the script but duplication, selling
## or transferring of this script is a violation of the
## copyright and purchase agreement.
##
## File Name: ab_showimage_funcs_ulib.php
## Version : 1.5.7.4
## Date : 2015-06-04   18:43:58
##########################################################
 */


function _create_image($url, $path, $width = null, $height = null, $recreate = false, $objout = false) {
    $exist = true;

    if (!is_file($path) || !file_exists($path)) {
        $exist = false;
        $path = static_path() . '/images/no-img.jpg';
        $url = static_url() . '/images/no-img.jpg';
    }

    if (!isset($width) && !isset($height)) {
            _makeUrlCDN($url, setting::get_data('images_cdn', 'val'),setting::get_data('images_cdn_zone', 'val'));
        return $url;
    }

    $i = @pengu_image::resize($path, $width, $height);
    if ($recreate && $exist)
        $i->ReCreate();
    if ($objout)
        return $i;
    return $i->getResult();
}

//////////////////////////////////////////
/* other images funcs */
function ab_image_path($file_name) {
    $path = '';
    if (!empty($file_name) && file_exists(ab_upload_dir . '/' . $file_name))
        $path = ab_upload_dir . '/' . $file_name;
    elseif (!empty($file_name) && file_exists(ab_tmp_dir . '/' . $file_name))
        $path = ab_tmp_dir . '/' . $file_name;
    return $path;
}

function ab_image_url($file_name) {
    $url = '';
    if (!empty($file_name) && file_exists(ab_upload_dir . '/' . $file_name))
        $url = ab_upload_url . '/' . $file_name;
    elseif (!empty($file_name) && file_exists(ab_tmp_dir . '/' . $file_name))
        $url = ab_tmp_url . '/' . $file_name;

    return $url;
}
function ab_create_img($file_name, $width = null, $height = null, $recreate = false, $objout = false) {
    $url = ab_image_url($file_name);
    $path = ab_image_path($file_name);
    return _create_image($url, $path, $width, $height, $recreate, $objout);
}

/* Game funcs */

function ab_game_thumb_path($file_name, $retFolder = false) {
    $path = '';
    if (!empty($file_name) && file_exists(ab_game_images_dir . '/' . $file_name))
        $path = ab_game_images_dir . (!$retFolder ? '/' . $file_name : null);
    elseif (!empty($file_name) && file_exists(ab_tmp_dir . '/' . $file_name))
        $path = ab_tmp_dir . (!$retFolder ? '/' . $file_name : null);
    elseif (!empty($file_name) && file_exists(ab_submission_images_dir . '/' . $file_name))
        $path = ab_submission_images_dir . (!$retFolder ? '/' . $file_name : null);
    return $path;
}

function ab_game_get_image_url($file_name, $retFolder = false) {
    $url = '';
    if (!empty($file_name) && file_exists(ab_game_images_dir . '/' . $file_name))
        $url = ab_game_images_url . (!$retFolder ? '/' . $file_name : null);
    elseif (!empty($file_name) && file_exists(ab_tmp_dir . '/' . $file_name))
        $url = ab_tmp_url . (!$retFolder ? '/' . $file_name : null);
    elseif (!empty($file_name) && file_exists(ab_submission_images_dir . '/' . $file_name))
        $url = ab_submission_images_url. (!$retFolder ? '/' . $file_name : null);

    return $url;
}

function ab_game_create_img($file_name, $width = null, $height = null, $recreate = false, $objout = false) {
    $url = ab_game_get_image_url($file_name);
    $path = ab_game_thumb_path($file_name);
    return _create_image($url, $path, $width, $height, $recreate, $objout);
}
